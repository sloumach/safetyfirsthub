<?php

namespace App\Repositories\Interfaces;

interface QuestionRepositoryInterface
{
    public function createWithChoices(int $examId, string $text, array $choices, int $correctIndex);
    public function updateWithChoices(int $questionId, string $text, array $choices, int $correctIndex);

}
