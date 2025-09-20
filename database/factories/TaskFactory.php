<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),//
'priority' => $this->faker->randomNumber(),
'content' => $this->faker->word(),
'dateline' => Carbon::now(),
'completed' => $this->faker->boolean(),
'created_at' => Carbon::now(),
'updated_at' => Carbon::now(),
        ];
    }
}
