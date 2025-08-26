<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CourseSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            EnrollmentSeeder::class,
            ReviewSeeder::class,
            LessonSeeder::class,
        ]);
    }
}
