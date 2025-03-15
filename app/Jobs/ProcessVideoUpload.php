<?php

namespace App\Jobs;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg; // Package FFMpeg pour Laravel

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $videoId;
    public $videoPath;

    /**
     * Create a new job instance.
     */
    public function __construct($videoId, $videoPath)
    {
        $this->videoId = $videoId;
        $this->videoPath = $videoPath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $video = Video::find($this->videoId);

        if (!$video) {
            \Log::error("Video not found for processing.");
            return;
        }

        $outputPath = "courses/videos/" . basename($this->videoPath);


        try {
            /* // 🔹 Conversion et compression avec FFmpeg
            FFMpeg::fromDisk('local')
                ->open($this->videoPath)
                ->export()
                ->toDisk('private') // 🔒 Stockage sécurisé
                ->inFormat(new \FFMpeg\Format\Video\X264)
                ->save($outputPath); */
            // 🔹 Déplacer le fichier sans compression
            Storage::disk('private')->put($outputPath, Storage::disk('local')->get($this->videoPath));

            // 🔹 Mise à jour du chemin final dans la base de données
            $video->update(['video_path' => $outputPath]);

            // 🔹 Suppression du fichier temporaire
            Storage::disk('local')->delete($this->videoPath);



        } catch (\Exception $e) {
            \Log::error("FFmpeg processing failed: " . $e->getMessage());
            // Réessaye après 30 secondes
        }
    }
}

