<?php

namespace App\Http\Controllers;

use App\Services\VideoProgressService;
use Illuminate\Http\Request;

class VideoProgressController extends Controller
{
    protected $videoProgressService;

    public function __construct(VideoProgressService $videoProgressService)
    {
        $this->videoProgressService = $videoProgressService;
    }

    // 📌 Mettre à jour la progression de la vidéo
    public function updateProgress(Request $request)
    {
        $response = $this->videoProgressService->updateProgress(
            $request->section_id,
            $request->video_id,
            $request->current_time,
            $request->total_duration
        );
        return response()->json($response);
    }

    // 📌 Vérifier la progression d'une vidéo
    public function checkProgress($course_id)
    {
        $response = $this->videoProgressService->checkProgress($course_id);
        return response()->json($response);
    }

    // 📌 Marquer une vidéo comme complétée
    public function markAsCompleted(Request $request)
{
    $response = $this->videoProgressService->markAsCompleted(
        $request->section_id,
        $request->video_id
    );
    return response()->json($response, $response['status'] ?? 200);
}


    // 📌 Réinitialiser la progression d'un cours
    public function resetProgress(Request $request)
    {
        $response = $this->videoProgressService->resetProgress($request->course_id);
        return response()->json($response);
    }

    public function checkCourseCompletion($course_id)
    {
        $isCompleted = $this->videoProgressService->isCourseCompleted($course_id);

        return response()->json([
            'completed' => $isCompleted,
            'message' => $isCompleted
                ? 'Course fully completed.'
                : 'Course not fully completed yet.'
        ]);
    }
}
