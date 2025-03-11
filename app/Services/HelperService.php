<?php

namespace App\Services;

use App\Services\VideoProgressService;


class HelperService 
{
    

    public static function checkCourseCompletion($course_id)
    {
        $videoProgressService = new VideoProgressService();
        $isCompleted = $videoProgressService->isCourseCompleted($course_id);

        return response()->json([
            'completed' => $isCompleted,
            'message' => $isCompleted
                ? 'Course fully completed.'
                : 'Course not fully completed yet.'
        ]);
    }
}



