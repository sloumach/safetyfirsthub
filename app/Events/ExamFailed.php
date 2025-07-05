<?php

namespace App\Events;

use App\Models\User;
use App\Models\Course;
use App\Models\ExamUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $user;
    public $course;
    public $examUser;
    public $attemptsLeft;

    public function __construct(User $user, Course $course, ExamUser $examUser, int $attemptsLeft)
    {
        $this->user = $user;
        $this->course = $course;
        $this->examUser = $examUser;
        $this->attemptsLeft = $attemptsLeft;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
