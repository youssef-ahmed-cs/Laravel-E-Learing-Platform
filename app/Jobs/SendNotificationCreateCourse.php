<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CourseCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendNotificationCreateCourse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function handle(): void
    {
//        $users = User::all();
        User::chunk(100, function ($users) {
            Notification::send($users, new CourseCreated($this->course));
        });
//        Notification::send($users, new CourseCreated($this->course));
    }
}
