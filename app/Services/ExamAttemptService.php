<?php

namespace App\Services;

use App\Models\ExamUser;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;

class ExamAttemptService
{
    public function submitAnswer($session_id, $question_id, $choice_id = null)
    {
        $user = Auth::user();
        $examUser = ExamUser::where('id', $session_id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examUser) {
            return ['error' => __('exam.no_active_session'), 'status' => 403];
        }

        $question = Question::where('id', $question_id)
            ->where('exam_id', $examUser->exam_id)
            ->first();

        if (!$question) {
            return ['error' => __('exam.invalid_question'), 'status' => 403];
        }

        if ($this->hasAlreadyAnswered($examUser->id, $question_id)) {
            return ['error' => __('exam.already_answered'), 'status' => 403];
        }

        $is_correct = $choice_id ? $this->isCorrectChoice($choice_id) : false;

        UserAnswer::create([
            'user_id' => $user->id,
            'exam_id' => $examUser->exam_id,
            'exam_user_id' => $examUser->id,
            'question_id' => $question_id,
            'choice_id' => $choice_id,
            'is_correct' => $is_correct,
        ]);

        return ['message' => __('exam.answer_saved')];
    }

    private function hasAlreadyAnswered($exam_user_id, $question_id)
    {
        return UserAnswer::where('exam_user_id', $exam_user_id)
            ->where('question_id', $question_id)
            ->exists();
    }

    private function isCorrectChoice($choice_id)
    {
        return Choice::where('id', $choice_id)
            ->where('is_correct', true)
            ->exists();
    }
}
