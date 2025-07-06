<?php

namespace App\Services;

use App\Repositories\Interfaces\ExamRepositoryInterface;

class AdminExamsManagement
{
    protected ExamRepositoryInterface $examRepo;

    public function __construct(ExamRepositoryInterface $examRepo)
    {
        $this->examRepo = $examRepo;
    }

    public function store(array $data)
    {
        return $this->examRepo->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->examRepo->update($id, $data);
    }

    public function get(int $id)
    {
        return $this->examRepo->findById($id);
    }
}
