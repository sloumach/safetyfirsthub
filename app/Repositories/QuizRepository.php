<?php

namespace App\Repositories;

use App\Models\SectionQuiz;
use App\Repositories\Interfaces\QuizRepositoryInterface;

class QuizRepository implements QuizRepositoryInterface
{
    public function create(array $data)
    {
        return SectionQuiz::create($data);
    }

    public function update(int $id, array $data)
    {
        $quiz = SectionQuiz::findOrFail($id);
        $quiz->update($data);
        return $quiz;
    }

    public function findById(int $id)
    {
        return SectionQuiz::findOrFail($id);
    }
}
