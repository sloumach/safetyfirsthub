<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Mail\CourseExpiringInFifteenDays;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCourseExpiringInFifteenDaysMail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function handle(CourseExpiringInFifteenDays $event): void
    {
        Mail::to($event->user->email)->send(
            new CourseExpiringInFifteenDays(
                $event->user,
                $event->course,
                $event->daysRemaining,
                $event->hasNotPassedExam
            )
        );
    }


}
