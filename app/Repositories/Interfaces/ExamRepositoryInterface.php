<?php

namespace App\Repositories\Interfaces;

interface ExamRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function findById(int $id);
}
