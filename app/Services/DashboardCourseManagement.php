<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ExamUser;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class DashboardCourseManagement
{
    public function getUserCourses(): array
    {
        $user = Auth::user();

        return $user->courses->map(function ($course) use ($user) {
            $coverUrl = $course->cover ? $this->getCoverUrl(basename($course->cover)) : null;

            $order = Order::where('user_id', $user->id)
                ->whereHas('course', fn($q) => $q->where('course_id', $course->id))
                ->latest()
                ->first();

            if (! $order) {
                return $this->formatCourseData($course, $coverUrl, false, null);
            }

            $examcheck = ExamUser::query()
                ->where('user_id', $user->id)
                ->where('order_id', $order->id)
                ->whereHas('exam', fn($q) => $q->where('course_id', $course->id))
                ->whereColumn('score', '>=', 'exams.passing_score')
                ->join('exams', 'exam_users.exam_id', '=', 'exams.id')
                ->latest('exam_users.id')
                ->value('exam_users.id');

            return $this->formatCourseData($course, $coverUrl, (bool) $examcheck, $examcheck);
        })->toArray();
    }

    protected function getCoverUrl(string $filename): string
    {
        return URL::temporarySignedRoute(
            'cover.access',
            now()->addMinutes(30),
            ['filename' => $filename]
        );
    }

    protected function formatCourseData($course, $coverUrl, $examcheck, $examId): array
    {
        return [
            'id'                => $course->id,
            'name'              => $course->name,
            'description'       => $course->description,
            'cover'             => $coverUrl,
            'total_videos'      => $course->total_videos,
            'students'          => $course->students ?? 0,
            'examcheck'         => $examcheck,
            'exam_id'           => $examId,
            'short_description' => $course->short_description,
        ];
    }
}
