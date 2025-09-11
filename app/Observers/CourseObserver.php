<?php

namespace App\Observers;

use App\Models\Course;
use Illuminate\Support\Str;

class CourseObserver
{
    public function creating(Course $course): void
    {
        $course->thumbnail === null ? $course->thumbnail = 'https://via.placeholder.com/640x480.png/00ccff?text=Course+Thumbnail' : null;
        $course->description = Str::limit($course->description, 60);
    }

    public function created(Course $course): void
    {
        $course->price = "$course->price $";
        $course->save();
    }

    public function updating(Course $course): void
    {
    }

    public function updated(Course $course): void
    {
    }

    public function saving(Course $course): void
    {
    }

    public function saved(Course $course): void
    {
    }

    public function deleted(Course $course): void
    {
    }

    public function restored(Course $course): void
    {
    }
}
