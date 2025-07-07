<?php

namespace App\Repositories\Interfaces;

interface QuizQuestionRepositoryInterface
{
    public function createWithChoices(int $quizId, string $text, array $choices, int $correctIndex);
    public function updateWithChoices(int $questionId, string $text, array $choices, int $correctChoiceId);
}

