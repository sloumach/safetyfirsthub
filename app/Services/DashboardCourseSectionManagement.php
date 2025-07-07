<?php

namespace App\Services;

use App\Models\Course;
use App\Models\VideoProgress;
use App\Models\UserSectionAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardCourseSectionManagement
{
    public function getCourseSections(int $courseId): array
    {
        $course = Course::with(['sections.videos', 'sections.slides', 'sections.quiz.questions.choices'])
            ->findOrFail($courseId);

        $userId = Auth::id();

        return $course->sections->map(function ($section) use ($userId) {
            $userAttempt = UserSectionAttempt::where('user_id', $userId)
                ->where('section_id', $section->id)
                ->orderByDesc('created_at')
                ->first();

            return [
                'id'    => $section->id,
                'title' => $section->title,
                'videos' => $section->videos->map(function ($video) use ($section, $userId) {
                    return [
                        'id'           => $video->id,
                        'title'        => $video->title,
                        'duration'     => $video->duration,
                        'video_url'    => url("/sections/{$section->id}/video"),
                        'is_completed' => VideoProgress::where('user_id', $userId)
                            ->where('video_id', $video->id)
                            ->where('is_completed', true)
                            ->exists(),
                    ];
                }),
                'slides' => $section->slides->map(fn($slide) => [
                    'id'      => $slide->id,
                    'title'   => $slide->title,
                    'content' => $slide->content,
                    'file'    => $slide->file_path,
                ]),
                'quiz' => $section->quiz ? [
                    'id'            => $section->quiz->id,
                    'passing_score' => $section->quiz->passing_score,
                    'is_attempted'  => $userAttempt !== null,
                    'is_passed'     => $userAttempt?->score >= $section->quiz->passing_score,
                    'score'         => $userAttempt?->score,
                    'questions'     => $section->quiz->questions->map(fn($question) => [
                        'id'      => $question->id,
                        'text'    => $question->question_text,
                        'choices' => $question->choices->map(fn($choice) => [
                            'id'   => $choice->id,
                            'text' => $choice->choice_text,
                        ]),
                    ]),
                ] : null,
            ];
        })->toArray();
    }
}
