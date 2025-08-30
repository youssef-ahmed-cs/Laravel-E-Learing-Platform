<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(),
            'imageable_id' => User::factory()->create()->id,
            'imageable_type' => User::class,
            'created_at' => now(),
        ];
    }
}
