<?php

namespace Database\Factories;

use App\Models\{Lesson, Task, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $now = Carbon::now();
        return [
            'title' => $this->faker->word(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'content' => $this->faker->word(),
            'dateline' => $now,
            'completed' => $this->faker->boolean(),
            'created_at' => $now,
            'updated_at' => $now,
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
        ];
    }
}
