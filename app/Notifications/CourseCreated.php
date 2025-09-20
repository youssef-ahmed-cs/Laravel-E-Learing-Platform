<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class CourseCreated extends Notification
{
    use Queueable;

    public object $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'A new course has been created: ' . $this->course->title,
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'course_description' => $this->course->description,
        ];
    }
}
