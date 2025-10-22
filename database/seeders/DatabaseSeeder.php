<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CourseSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            EnrollmentSeeder::class,
            ReviewSeeder::class,
            LessonSeeder::class,
            ProfileSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
