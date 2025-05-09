<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use App\Services\ExamAttemptService;
use Illuminate\Http\Request;

class UserExamsController extends Controller
{
    protected $examService;
    protected $examAttemptService;

    public function __construct(ExamService $examService, ExamAttemptService $examAttemptService)
    {
        $this->examService = $examService;
        $this->examAttemptService = $examAttemptService;
    }

    public function startExam($course_id)
    {
        $response = $this->examService->startExam($course_id);
        return response()->json($response, $response['status'] ?? 200);
    }

    public function submitAnswer(Request $request, $session_id)
    {
        $response = $this->examAttemptService->submitAnswer(
            $session_id,
            $request->question_id,
            $request->choice_id
        );
        return response()->json($response, $response['status'] ?? 200);
    }
}
