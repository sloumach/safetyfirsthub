<?php
namespace App\Services;

use App\Models\Exam;
use App\Models\Choice;
use App\Models\ExamUser;
use App\Models\VideoProgress;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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


    private function finalizeExam($examUser)
    {
        $totalQuestions = Question::where('exam_id', $examUser->exam_id)->count();
        $correctAnswers = UserAnswer::where('exam_user_id', $examUser->id)->where('is_correct', true)->count();

        $score  = round(($correctAnswers / $totalQuestions) * 100);
        $status = $score >= $examUser->exam->passing_score ? 'passed' : 'failed';

        HelperService::markExamAsCompleted($examUser->id, $score, $status);
        $status === 'failed' ? HelperService::resetAllVideos($examUser) : null;

        // 📌 Vérifier le nombre total de tentatives échouées
        if ($status === 'failed') {
            $attemptsCount = ExamUser::where('user_id', $examUser->user_id)
                ->where('exam_id', $examUser->exam_id)
                ->where('status', 'completed')
                ->where('score', '<', $examUser->exam->passing_score) // Vérifie si les tentatives étaient des échecs
                ->count();

            if ($attemptsCount >= 3) {
                $courseId = $examUser->exam->course_id;
                $userId = $examUser->user_id;
                $user = User::find($userId);
                
                // 📌 Supprimer l'accès au cours
                $user->courses()->detach($courseId);
                $courseCount = User::find($userId)->courses()
                ->whereNotNull('course_user.created_at') // 🔥 Ajout du préfixe 'course_user.'
                ->count();
                Log::info($courseCount);
                if ($courseCount == 0) {
                    Log::info("role change");
                    // 📌 Gérer les rôles de l'utilisateur
                    $studentRole = Role::where('name', 'student')->first();
                    $userRole = Role::where('name', 'user')->first();
                    $user->roles()->detach($studentRole->id); // Retirer "student"
                    $user->roles()->syncWithoutDetaching([$userRole->id]); // Ajouter "user"

                    return [
                        'exam_completed' => true,
                        'score'          => $score,
                        'status'         => 200,
                        'examresult'     => $status,
                        'passing_score'  => $examUser->exam->passing_score,
                        'retry_allowed'  => $status === 'failed' && $examUser->attempts < 3,
                        'attempts_left'  => 3 - $examUser->attempts,
                        'role_changed'  => 1,
                    ];
                }
                   
            }
        }

        return [
            'exam_completed' => true,
            'score'          => $score,
            'status'         => 200,
            'examresult'     => $status,
            'passing_score'  => $examUser->exam->passing_score,
            'retry_allowed'  => $status === 'failed' && $examUser->attempts < 3,
            'attempts_left'  => 3 - $examUser->attempts,
            'role_changed'  => 0,
        ];
    }
}
