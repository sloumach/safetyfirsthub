<?php

namespace App\Listeners;

use App\Events\ExamFailed;
use App\Mail\ExamFailedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendExamFailedNotification implements ShouldQueue
{
    public function handle(ExamFailed $event)
    {
        Mail::to($event->user->email)
            ->send(new ExamFailedNotification(
                $event->user,
                $event->course,
                $event->attemptsLeft
            ));
    }
}
