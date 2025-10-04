<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    public function isPremium(): static
    {
        return $this->where('is_premium', true);
    }

    public function recentFirst(): static
    {
        return $this->orderBy('created_at', 'desc')->limit(5);
    }

    public function studentsWithCourses() : static
    {
        return $this->where('role', 'student')->with('courses');
    }
}
