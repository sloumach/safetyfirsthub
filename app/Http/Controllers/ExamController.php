<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;
use App\Models\ExamUser;
use App\Services\HelperService;
use App\Services\ExamAttemptService;
class ExamController extends Controller
{
    protected $examService;
    protected $examAttemptService;
    public function __construct(ExamService $examService, ExamAttemptService $examAttemptService)
    {
        $this->examService = $examService;
        $this->examAttemptService = $examAttemptService;
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
        $examUser = ExamUser::where('id', $session_id)
            ->where('status', 'in_progress')
            ->with('exam.questions.choices') // 🔹 Précharge les relations nécessaires
            ->first();
            $result= $this->examAttemptService->finalizeExam($examUser);
            return ($result);
        /* HelperService::markExamAsCompleted($session_id,0,'completed');
        HelperService::resetAllVideos($examUser) ;

        return response()->json([
            'exam_completed' => true,
            'message' => 'Exam cancelled and it will marked as failed',
        ], 200); */
    }

    // 📌 Récupérer l'historique des examens d'un utilisateur
    public function userExamHistory()
    {
        $response = $this->examService->userExamHistory();
        return response()->json($response, $response['status'] ?? 200);
    }
}
