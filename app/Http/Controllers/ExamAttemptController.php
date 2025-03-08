<?php

namespace App\Http\Controllers;

use App\Services\ExamAttemptService;
use Illuminate\Http\Request;

class ExamAttemptController extends Controller
{
    protected $examAttemptService;

    public function __construct(ExamAttemptService $examAttemptService)
    {
        $this->examAttemptService = $examAttemptService;
    }

    // 📌 Récupérer la prochaine question d'un examen
    public function getNextQuestion($session_id)
    {
        $response = $this->examAttemptService->getNextQuestion($session_id);
        return response()->json($response, $response['status'] ?? 200);
    }

    // 📌 Soumettre une réponse à une question
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
