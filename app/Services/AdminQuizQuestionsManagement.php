<?php


namespace App\Services;

use App\Repositories\Interfaces\QuizQuestionRepositoryInterface;

class AdminQuizQuestionsManagement
{
    protected QuizQuestionRepositoryInterface $quizQuestionRepo;

    public function __construct(QuizQuestionRepositoryInterface $quizQuestionRepo)
    {
        $this->quizQuestionRepo = $quizQuestionRepo;
    }

    public function store(int $quizId, string $text, array $choices, int $correctIndex)
    {
        return $this->quizQuestionRepo->createWithChoices($quizId, $text, $choices, $correctIndex);
    }

    public function update(int $questionId, string $text, array $choices, int $correctChoiceId)
    {
        return $this->quizQuestionRepo->updateWithChoices($questionId, $text, $choices, $correctChoiceId);
    }
}
