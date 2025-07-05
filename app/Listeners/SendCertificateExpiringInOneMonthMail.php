<?php

namespace App\Listeners;

use App\Events\CertificateExpiringInOneMonth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateOneMonthMail;

class SendCertificateExpiringInOneMonthMail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function handle(CertificateExpiringInOneMonth $event): void
    {
        $certificate = $event->certificate;
        $user = $certificate->user;

        Mail::to($user->email)
            ->send(new CertificateOneMonthMail($certificate));
    }
}
