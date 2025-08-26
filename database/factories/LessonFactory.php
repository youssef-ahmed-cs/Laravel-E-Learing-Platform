<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'content' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(1, 10),
            'video_url' => $this->faker->url(),
            'is_free' => true,
            'course_id' => Course::factory()->create()->id,
            'duration' => $this->faker->numberBetween(1, 120),
        ];
    }
}
