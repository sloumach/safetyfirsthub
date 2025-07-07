<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Events\CourseExpiringInOneMonth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\CourseExpiringInOneMonth as OneMonthMail;


class SendCourseExpiringInOneMonthMail implements ShouldQueue
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
    public function handle(CourseExpiringInOneMonth $event): void
    {
        Mail::to($event->user->email)->send(
            new OneMonthMail(
                $event->user,
                $event->course,
                $event->daysRemaining,
                $event->hasNotPassedExam
            )
        );
    }
}
