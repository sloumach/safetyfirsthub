<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Repositories\Interfaces\ExamRepositoryInterface;

class ExamRepository implements ExamRepositoryInterface
{
    public function create(array $data)
    {
        return Exam::create($data);
    }

    public function update(int $id, array $data)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($data);
        return $exam;
    }

    public function findById(int $id)
    {
        return Exam::findOrFail($id);
    }
}
