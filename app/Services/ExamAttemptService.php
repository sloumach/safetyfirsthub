<?php
namespace App\Services;

use Mail;
use App\Models\Exam;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Choice;
use App\Models\Payment;
use App\Models\ExamUser;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\VideoProgress;
use App\Models\UserSectionAttempt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Mail\ExamFailedNotification;
use App\Mail\ExamPassedNotification;

class ExamAttemptService
{
    public function submitAnswer($session_id, $question_id, $choice_id = null)
    {
        $user     = Auth::user();
        $examUser = ExamUser::where('id', $session_id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if (! $examUser) {
            return ['error' => __('exam.no_active_session'), 'status' => 403];
        }

        $question = Question::where('id', $question_id)
            ->where('exam_id', $examUser->exam_id)
            ->first();

        if (! $question) {
            return ['error' => __('exam.invalid_question'), 'status' => 403];
        }

        // 🔹 Vérifier si la question a déjà été répondue et si la réponse est correcte en une seule requête
        $existingAnswer = UserAnswer::where('exam_user_id', $examUser->id)
            ->where('question_id', $question_id)
            ->first();

        if ($existingAnswer) {
            return ['error' => __('exam.already_answered'), 'status' => 403];
        }

        $is_correct = $choice_id ? Choice::where('id', $choice_id)->where('is_correct', true)->exists() : false;

        UserAnswer::create([
            'user_id'      => $user->id,
            'exam_id'      => $examUser->exam_id,
            'exam_user_id' => $examUser->id,
            'question_id'  => $question_id,
            'choice_id'    => $choice_id,
            'is_correct'   => $is_correct,
        ]);

        // 🔹 Vérifier si toutes les questions ont été répondues
        return $this->getNextQuestion($session_id);
    }

    public function getNextQuestion($session_id)
    {
        $user = Auth::user();

        // 📌 Vérification de la session active
        $examUser = ExamUser::where('id', $session_id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->with('exam.questions.choices') // 🔹 Précharge les relations nécessaires
            ->first();

        if (!$examUser) {
            return ['error' => __('exam.invalid_session'), 'status' => 403];
        }

        // 📌 Récupérer l'examen
        $exam = $examUser->exam;

        // 📌 Récupérer les ID des questions déjà répondues
        $answeredQuestions = UserAnswer::where('exam_user_id', $examUser->id)
            ->pluck('question_id');

        // 📌 Trouver la prochaine question non répondue
        $nextQuestion = $exam->questions()
            ->whereNotIn('id', $answeredQuestions)
            ->orderBy('id')
            ->first();

        // 📌 Si toutes les questions ont été répondues, finaliser l'examen
        if (!$nextQuestion) {
            Log::info('nextQuestion');
            //return ['exam_completed' => true];
            return $this->finalizeExam($examUser);
        }
        Log::info('return false');
        return [
            'question' => [
                'id' => $nextQuestion->id,
                'text' => $nextQuestion->question_text,
                'choices' => $nextQuestion->choices->map(fn($choice) => [
                    'id' => $choice->id,
                    'text' => $choice->choice_text,
                ]),
            ],
            'exam_completed' => false,
        ];
    }


    public function finalizeExam($examUser)
    {
        $exam     = $examUser->exam;
        $user     = $examUser->user;
        $course   = $exam->course;
        $courseId = $course->id;
        $userId   = $user->id;

        // 🎯 Calcul du score
        $totalQuestions  = Question::where('exam_id', $exam->id)->count();
        $correctAnswers  = UserAnswer::where('exam_user_id', $examUser->id)->where('is_correct', true)->count();
        $score           = round(($correctAnswers / $totalQuestions) * 100);
        $status          = $score >= $exam->passing_score ? 'passed' : 'failed';

        // ✅ Marquer l'examen comme terminé
        HelperService::markExamAsCompleted($examUser->id, $score, $status);

        // 🔄 Compter les tentatives échouées pour ce cours
        $attemptsCount = ExamUser::where('user_id', $userId)
            ->whereHas('exam', fn($q) => $q->where('course_id', $courseId))
            ->where('status', 'completed')
            ->where('score', '<', $exam->passing_score)
            ->count();

        $attemptsLeft = max(0, 3 - $attemptsCount);

        // 📧 Notifications par email
        if ($status === 'failed') {
            Mail::to($user->email)->send(new ExamFailedNotification($user, $course, $attemptsLeft));
            HelperService::resetAllVideos($examUser);
        } else {
            Mail::to($user->email)->send(new ExamPassedNotification($user, $course));
        }

        // 🚫 Si 3 tentatives échouées, on révoque l'accès
        if ($status === 'failed' && $attemptsCount >= 3) {
            // 🧹 Nettoyage des données liées au cours
            ExamUser::where('user_id', $userId)
                ->whereHas('exam', fn($q) => $q->where('course_id', $courseId))
                ->delete();

            Payment::where('user_id', $userId)
                ->whereHas('orders', fn($q) => $q->where('course_id', $courseId))
                ->delete();

            Order::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->delete();

            UserSectionAttempt::where('user_id', $userId)
                ->whereHas('section', fn($q) => $q->where('course_id', $courseId))
                ->delete();

            $user->courses()->detach($courseId);

            // 🧠 Vérifier s’il reste des cours actifs
            $remainingCourses = $user->courses()
                ->whereNotNull('course_user.created_at')
                ->count();

            // 👤 Mise à jour du rôle si plus aucun cours actif
            if ($remainingCourses === 0) {
                Log::info("User #$userId - role downgraded from 'student' to 'user' after failing all courses.");

                $studentRole = Role::where('name', 'student')->first();
                $userRole    = Role::where('name', 'user')->first();

                $user->roles()->detach($studentRole->id);
                $user->roles()->syncWithoutDetaching([$userRole->id]);

                return [
                    'exam_completed' => true,
                    'score'          => $score,
                    'status'         => 200,
                    'examresult'     => $status,
                    'passing_score'  => $exam->passing_score,
                    'retry_allowed'  => false,
                    'attempts_left'  => 0,
                    'role_changed'   => 1,
                    'message'        => 'Exam failed. You have exceeded your maximum attempts and access has been revoked. Please repurchase the course to try again.',
                ];
            }
        }

        // 🔚 Résultat par défaut
        return [
            'exam_completed' => true,
            'score'          => $score,
            'status'         => 200,
            'examresult'     => $status,
            'passing_score'  => $exam->passing_score,
            'retry_allowed'  => $status === 'failed' && $examUser->attempts < 3,
            'attempts_left'  => $attemptsLeft,
            'role_changed'   => 0,
        ];
    }

}
