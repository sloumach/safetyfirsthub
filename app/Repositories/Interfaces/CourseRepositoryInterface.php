<?php

namespace App\Repositories\Interfaces;

use App\Models\Course;

interface CourseRepositoryInterface
{
    public function createCourse(array $data): Course;
}

