<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamUser;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\ExamStatus;

class ExamService
{
    public function startExam($course_id)
    {
        $user = Auth::user();

        if (!$this->userHasAccessToCourse($user, $course_id)) {
            return ['error' => __('exam.access_denied'), 'status' => 403];
        }

        $order = $this->getLastOrderForCourse($user->id, $course_id);
        if (!$order) {
            return ['error' => __('exam.no_valid_purchase'), 'status' => 403];
        }

        $exam = Exam::where('course_id', $course_id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$exam) {
            return ['error' => __('exam.no_exam_available'), 'status' => 404];
        }

        if ($this->hasExceededAttempts($user->id, $order->id)) {
            return ['error' => __('exam.max_attempts_reached'), 'status' => 403];
        }

        $sessionId = DB::table('exam_users')->insertGetId([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'order_id' => $order->id,
            'status' => ExamStatus::IN_PROGRESS->value,
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'session_id' => $sessionId,
            'exam' => $exam,
        ];
    }

    private function userHasAccessToCourse($user, $course_id)
    {
        return $user->courses->contains($course_id);
    }

    private function getLastOrderForCourse($user_id, $course_id)
    {
        return Order::where('user_id', $user_id)
            ->whereHas('course', fn($query) => $query->where('course_id', $course_id))
            ->latest()
            ->first();
    }

    private function hasExceededAttempts($user_id, $order_id)
    {
        return ExamUser::where('user_id', $user_id)
            ->where('order_id', $order_id)
            ->count() >= 3;
    }
}
