<?php

namespace App\Services;

use App\Models\ExamUser;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardExamManagement
{
    public function getPassedExams(): \Illuminate\Support\Collection
    {
        $userId = Auth::id();

        $passedExams = ExamUser::where('exam_users.user_id', $userId)
            ->whereColumn('score', '>=', 'exams.passing_score')
            ->join('exams', 'exam_users.exam_id', '=', 'exams.id')
            ->join('courses', 'exams.course_id', '=', 'courses.id')
            ->select(
                'exam_users.id as exam_user_id',
                'exams.id as exam_id',
                'exams.title as exam_title',
                'exams.passing_score',
                'exam_users.score',
                'exam_users.completed_at',
                'courses.id as course_id',
                'courses.name as course_name',
                'courses.cover as course_cover'
            )
            ->get();

        return $passedExams->transform(function ($exam) {
            $exam->course_cover = $exam->course_cover
                ? $this->getCoverUrl(basename($exam->course_cover))
                : null;
            return $exam;
        });
    }

    protected function getCoverUrl(string $filename): string
    {
        return URL::temporarySignedRoute(
            'cover.access',
            now()->addMinutes(30),
            ['filename' => $filename]
        );
    }
}
