<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Certificate;
use Illuminate\Console\Command;
use App\Mail\CertificateExpired;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateExpiringInOneMonth;
use App\Mail\CertificateExpiringInFifteenDays;

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
            $user = $examUser?->user;
            // On vérifie que tout est bien chargé et que la durée est définie
            if (!$user || !$course || !$course->duration) {
                continue;
            }

            // Calcul de la date d’expiration
            $expirationDate = $certificate->created_at->copy()->addMonths($course->duration);

            $now = now();

            $daysUntilExpiration = $now->diffInDays($expirationDate, false);

            if ($daysUntilExpiration <= 0) {
                // Expiré
                $certificate->update(['available' => false]);
                Mail::to($user->email)->send(new CertificateExpired($certificate));
                $expiredCount++;
            } elseif ($daysUntilExpiration === 15) {
                // 15 jours avant
                Mail::to($user->email)->send(new CertificateExpiringInFifteenDays($certificate));
            } elseif ($daysUntilExpiration === 30) {
                // 1 mois avant
                Mail::to($user->email)->send(new CertificateExpiringInOneMonth($certificate));
            }
        }

        $this->info("$expiredCount certificat(s) expiré(s) ont été désactivé(s).");
    }
}
