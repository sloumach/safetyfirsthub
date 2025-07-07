<?php

namespace App\Listeners;

use App\Events\CertificateExpired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\CertificateExpiredMail;
use Illuminate\Support\Facades\Mail;

class SendCertificateExpiredMail implements ShouldQueue
{
    public function handle(CertificateExpired $event)
    {
        $user = $event->certificate->user;
        Mail::to($user->email)->send(new CertificateExpiredMail($event->certificate));
    }
}
