<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
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
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'instructor_id' => User::factory()->create()->id,
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'status' => $this->faker->randomElement(['draft', 'published','archived']),
            'duration' => $this->faker->numberBetween(1, 120),
            'category_id' => Category::factory()->create()->id,
        ];
    }
}
