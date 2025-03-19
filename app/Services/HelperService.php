<?php

namespace App\Services;

use App\Services\VideoProgressService;
use App\Models\ExamUser;
use App\Models\VideoProgress;
use App\Models\UserSectionAttempt;
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
    public static function markExamAsCompleted($examuser, $score, $status)
    {
        $examUser = ExamUser::find($examuser);

        $examUser->update([
            'status'       => 'completed',
            'score'        => $score,
            'completed_at' => now(),
        ]);       
        
    }
    public static function resetAllVideos($examUser)
    {
        VideoProgress::where('user_id', $examUser->user_id)
        ->whereHas('video.section', function ($query) use ($examUser) {
            $query->where('course_id', $examUser->exam->course_id);
        })
        ->update(['is_completed' => 0]);      
        UserSectionAttempt::where('user_id', $examUser->user_id)
        ->whereHas('section', function ($query) use ($examUser) {
            $query->where('course_id', $examUser->exam->course_id);
        })
        ->delete();
    }
}



