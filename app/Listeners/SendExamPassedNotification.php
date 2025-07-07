<?php

namespace App\Listeners;

use App\Events\ExamPassed;
use App\Mail\ExamPassedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendExamPassedNotification implements ShouldQueue
{
    public function handle(ExamPassed $event)
    {
        Mail::to($event->user->email)
            ->send(new ExamPassedNotification(
                $event->user,
                $event->course
            ));
    }
}
