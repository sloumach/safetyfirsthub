<?php
namespace App\Services;

use App\Models\Exam;
use App\Models\Choice;
use App\Models\ExamUser;
use App\Models\VideoProgress;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;

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
            //return ['exam_completed' => true];
            return $this->finalizeExam($examUser);
        }

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

        $examUser->update([
            'status'       => 'completed',
            'score'        => $score,
            'completed_at' => now(),
        ]);
         // 📌 Si l'examen est échoué, réinitialiser la progression des vidéos du cours
        if ($status === 'failed') {
            VideoProgress::where('user_id', $examUser->user_id)
                ->whereHas('video.section', function ($query) use ($examUser) {
                    $query->where('course_id', $examUser->exam->course_id);
                })
                ->update(['is_completed' => 0]);
        }

        return [
            'exam_completed' => true,
            'score'          => $score,
            'status'         => 200,
            'examresult'         => $status,
            'passing_score'  => $examUser->exam->passing_score,
            'retry_allowed'  => $status === 'failed' && $examUser->attempts < 3,
            'attempts_left'  => 3 - $examUser->attempts,
        ];
    }
}
