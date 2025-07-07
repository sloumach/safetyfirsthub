<?php

namespace App\Repositories;

use App\Models\SectionQuizQuestion;
use App\Models\SectionQuizChoice;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\QuizQuestionRepositoryInterface;

class QuizQuestionRepository implements QuizQuestionRepositoryInterface
{
    public function createWithChoices(int $quizId, string $text, array $choices, int $correctIndex)
    {
        return DB::transaction(function () use ($quizId, $text, $choices, $correctIndex) {
            $question = SectionQuizQuestion::create([
                'quiz_id' => $quizId,
                'question_text' => $text,
            ]);

            foreach ($choices as $index => $choiceText) {
                SectionQuizChoice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceText,
                    'is_correct' => ($index + 1 == $correctIndex),
                ]);
            }

            return $question;
        });
    }

    public function updateWithChoices(int $questionId, string $text, array $choices, int $correctChoiceId)
    {
        return DB::transaction(function () use ($questionId, $text, $choices, $correctChoiceId) {
            $question = SectionQuizQuestion::findOrFail($questionId);
            $question->update(['question_text' => $text]);

            foreach ($question->choices as $index => $choice) {
                $choice->update([
                    'choice_text' => $choices[$index] ?? $choice->choice_text,
                    'is_correct' => ($choice->id == $correctChoiceId),
                ]);
            }

            return $question;
        });
    }
}

