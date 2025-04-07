<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Certificate;
use Carbon\Carbon;

class ExpireCertificates extends Command
{
    protected $signature = 'certificates:expire';
    protected $description = 'Expire les certificats selon la durée du cours associé';

    public function handle()
    {
        // Eager load les relations nécessaires
        $certificates = Certificate::where('available', true)
            ->with('examUser.exam.course')
            ->get();

        $expiredCount = 0;

        foreach ($certificates as $certificate) {
            $examUser = $certificate->examUser;
            $exam = $examUser?->exam;
            $course = $exam?->course;

            // On vérifie que tout est bien chargé et que la durée est définie
            if (!$course || !$course->duration) {
                continue;
            }

            // Calcul de la date d’expiration
            $expirationDate = $certificate->created_at->copy()->addMonths($course->duration);

            if (now()->greaterThan($expirationDate)) {
                $certificate->update(['available' => false]);
                $expiredCount++;
            }
        }

        $this->info("$expiredCount certificat(s) expiré(s) ont été désactivé(s).");
    }
}
