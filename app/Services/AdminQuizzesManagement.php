<?php

namespace App\Services;

use App\Repositories\Interfaces\QuizRepositoryInterface;

class AdminQuizzesManagement
{
    protected QuizRepositoryInterface $quizRepo;

    public function __construct(QuizRepositoryInterface $quizRepo)
    {
        $this->quizRepo = $quizRepo;
    }

    public function store(array $data)
    {
        return $this->quizRepo->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->quizRepo->update($id, $data);
    }

    public function get(int $id)
    {
        return $this->quizRepo->findById($id);
    }
}
