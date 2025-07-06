<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Models\Question;
use App\Repositories\Interfaces\QuestionRepositoryInterface;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function createWithChoices(int $examId, string $text, array $choices, int $correctIndex)
    {
        $exam = Exam::findOrFail($examId);
        $question = $exam->questions()->create(['question_text' => $text]);

        foreach ($choices as $index => $choiceText) {
            $question->choices()->create([
                'choice_text' => $choiceText,
                'is_correct' => ($index + 1 == $correctIndex),
            ]);
        }

        return $question;
    }

    public function updateWithChoices(int $questionId, string $text, array $choices, int $correctIndex)
    {
        $question = Question::findOrFail($questionId);
        $question->update(['question_text' => $text]);

        foreach ($question->choices as $index => $choice) {
            $choice->update([
                'choice_text' => $choices[$index],
                'is_correct' => ($index + 1 == $correctIndex),
            ]);
        }

        return $question;
    }
}

