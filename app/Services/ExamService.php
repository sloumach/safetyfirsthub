<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamUser;
use App\Models\Order;
use App\Services\HelperService;
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

        /* $order = $this->getLastOrderForCourse($user->id, $course_id);
        if (!$order) {
            return ['error' => __('exam.no_valid_purchase'), 'status' => 403];
        } */

        /* if(!HelperService::checkCourseCompletion($course_id)->completed){
            return ['error' => __('exam.course_not_completeddd'), 'status' => 403];
        } */
        $attemptedExamIds = ExamUser::where('user_id', $user->id)
            ->pluck('exam_id')
            ->toArray();
        $availableExams = Exam::where('course_id', $course_id)
            ->where('is_active', true)
            ->whereNotIn('id', $attemptedExamIds)
            ->get();

        // Log the attempted exam IDs and available exams
        \Log::info('Attempted Exam IDs:', ['user_id' => $user->id, 'attempted_exam_ids' => $attemptedExamIds]);
        \Log::info('Available Exams:', ['course_id' => $course_id, 'available_exams' => $availableExams->pluck('id')->toArray()]);
        if ($availableExams->isEmpty()) {
            return ['error' => __('exam.no_exam_available'), 'status' => 404];
        }
        $exam = $availableExams->random();


        if (!$exam) {
            return ['error' => __('exam.no_exam_available'), 'status' => 404];
        }

        // Check if user has exhausted attempts
        $attemptsCount = ExamUser::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->count();

        if ($attemptsCount >= 3) {
            return [
                'error' => __('exam.max_attempts_reached'),
                'status' => 403,
                'attempts_exhausted' => true
            ];
        }

        /* if ($this->hasExceededAttempts($user->id, $order->id)) {
            return ['error' => __('exam.max_attempts_reached'), 'status' => 403];
        } */

        $sessionId = DB::table('exam_users')->insertGetId([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            /* 'order_id' => $order->id, */
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

    public function userExamHistory()
    {
        $user = Auth::user();

        $examHistory = ExamUser::where('user_id', $user->id)
            ->with(['exam' => function($query) {
                $query->with('course:id,name');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($examUser) {
                return [
                    'id' => $examUser->id,
                    'score' => $examUser->score,
                    'status' => $examUser->status,
                    'completed_at' => $examUser->completed_at,
                    'exam' => [
                        'id' => $examUser->exam->id,
                        'course_id' => $examUser->exam->course_id,
                        'name' => $examUser->exam->course->name
                    ]
                ];
            });

        return $examHistory;
    }

    public function examResults($session_id)
    {
        $user = Auth::user();

        $examUser = ExamUser::where('exam_id', $session_id)
            ->where('user_id', $user->id)
            ->with(['exam.course:id,name'])
            ->first();

        if (!$examUser) {
            return null;
        }

        return [
            'exam_user_id' => $examUser->id,
            'score' => $examUser->score,
            'completed_at' => $examUser->completed_at,
            'user_firstname' => $user->firstname,
            'user_lastname' => $user->lastname,
            'course_name' => $examUser->exam->course->name
        ];
    }

}
