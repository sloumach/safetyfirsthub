<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    // 📌 Démarrer un examen
    public function startExam($course_id)
    {
        $response = $this->examService->startExam($course_id);
        return response()->json($response, $response['status'] ?? 200);
    }

    // 📌 Récupérer les résultats d'un examen
    public function examResults($session_id)
    {
        $response = $this->examService->examResults($session_id);
        return response()->json($response, $response['status'] ?? 200);
    }

    // 📌 Marquer un examen comme complété
    public function markExamAsCompleted($session_id)
    {
        $response = $this->examService->markExamAsCompleted($session_id);
        return response()->json($response, $response['status'] ?? 200);
    }

    // 📌 Récupérer l'historique des examens d'un utilisateur
    public function userExamHistory()
    {
        $response = $this->examService->userExamHistory();
        return response()->json($response, $response['status'] ?? 200);
    }
}
