<?php

namespace App\Services;

use App\Repositories\Interfaces\QuestionRepositoryInterface;

class AdminQuestionsManagement
{
    protected QuestionRepositoryInterface $questionRepo;

    public function __construct(QuestionRepositoryInterface $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    public function store(int $examId, string $text, array $choices, int $correctIndex)
    {
        return $this->questionRepo->createWithChoices($examId, $text, $choices, $correctIndex);
    }

    public function update(int $questionId, string $text, array $choices, int $correctIndex)
    {
        return $this->questionRepo->updateWithChoices($questionId, $text, $choices, $correctIndex);
    }
}
