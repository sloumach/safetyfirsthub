<?php
namespace App\Services;

use App\Models\Course;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;

class VideoProgressService
{
    public function isCourseCompleted($course_id)
    {
        $user   = Auth::user();
        $course = Course::with('sections.videos')->findOrFail($course_id);

        // Vérifier si toutes les vidéos du cours sont complétées
        foreach ($course->sections as $section) {
            foreach ($section->videos as $video) {
                $progress = VideoProgress::where('user_id', $user->id)
                    ->where('video_id', $video->id)
                    ->where('is_completed', true)
                    ->exists();

                if (! $progress) {
                    return false;
                }
            }
        }

        return true;
    }

    public function updateProgress($section_id, $video_id, $current_time, $total_duration)
    {
        $user = Auth::user();

        // 📌 Vérifier si la vidéo est déjà complétée
        $progress = VideoProgress::where('user_id', $user->id)
            ->where('section_id', $section_id)
            ->where('video_id', $video_id)
            ->first();

        if ($progress && $progress->is_completed) {
            return ['message' => 'video.already_completed', 'is_completed' => true];
        }

        // 📌 Si aucun enregistrement trouvé, on le crée
        $progress = VideoProgress::firstOrCreate(
            ['user_id' => $user->id, 'section_id' => $section_id, 'video_id' => $video_id],
            ['total_duration' => $total_duration, 'watched_segments' => json_encode([])]
        );

        $segments   = json_decode($progress->watched_segments, true) ?? [];
        $segments[] = $current_time;

        $isCompleted = $this->checkCompletion([3], 420);
        // 📌 Mettre à jour uniquement si la vidéo n'est pas encore complétée
        if (!$progress->is_completed) {
            $progress->update([
                'watched_segments' => json_encode($segments),
                'is_completed'     => $isCompleted,
            ]);
        }

        return ['message' => 'video.progress_updated', 'is_completed' => $progress->is_completed];
    }


    private function checkCompletion($segments, $total_duration)
    {
        sort($segments);
        $segments = array_unique($segments); // Éviter les doublons

        // Définir l'intervalle maximum pour qu'un segment soit valide
        $expectedInterval = 10; // 10 secondes minimum regardées pour chaque segment
        $watched_time     = 0;
        $previous_time    = null;

        foreach ($segments as $time) {
            if ($previous_time === null) {
                $watched_time += $expectedInterval; // Premier segment valide
            } else {
                $gap = $time - $previous_time;
                if ($gap <= $expectedInterval + 2) { // Si l'écart est raisonnable (max 12s)
                    $watched_time += $gap;
                }
            }
            $previous_time = $time;
        }

        // Définir le seuil de complétion (99% du total)
        $completion_threshold = $total_duration * 0.99;

        return $watched_time >= $completion_threshold;
    }


    public function markAsCompleted($section_id, $video_id)
    {
        $user = Auth::user();

        // Vérifier si la progression existe
        $progress = VideoProgress::where('user_id', $user->id)
            ->where('section_id', $section_id)
            ->where('video_id', $video_id)
            ->first();

        if (! $progress) {
            return ['error' => 'No progress found for this video.', 'status' => 404];
        }

        // Marquer la vidéo comme complétée
        $progress->update([
            'is_completed' => true,
        ]);

        // Vérifier si toutes les vidéos de la section sont complétées
        $allVideosCompleted = VideoProgress::where('user_id', $user->id)
            ->where('section_id', $section_id)
            ->where('is_completed', false)
            ->doesntExist();

        return [
            'message'           => 'video.completed',
            'section_completed' => $allVideosCompleted,
        ];
    }

}
