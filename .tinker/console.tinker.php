<?php #Tinker console
use App\Models\{Enrollment, Task, User, Course, Profile};

$user = User::firstOrCreate(
    ['username' => 'saf'],
);
