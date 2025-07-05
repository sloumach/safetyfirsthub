<?php

namespace App\Listeners;

use App\Events\CertificateExpiringInFifteenDays;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\CertificateFifteenMail;

class SendCertificateExpiringInFifteenDaysMail implements ShouldQueue
{
    public function handle(CertificateExpiringInFifteenDays $event): void
    {
        $certificate = $event->certificate;
        $user = $certificate->user;

        Mail::to($user->email)
            ->send(new CertificateFifteenMail($certificate));
    }
}
