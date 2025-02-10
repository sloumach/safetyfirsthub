<?php

namespace App\Listeners;

use App\Events\CoursePurchased;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\PurchaseConfirmationMail;
use Illuminate\Support\Facades\Mail;

class SendPurchaseConfirmationEmail implements ShouldQueue

{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CoursePurchased $event): void
    {
        Mail::to($event->user->email)->send(new PurchaseConfirmationMail($event->user, $event->course));
    }
}
