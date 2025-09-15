<?php #Tinker
use App\Models\{Enrollment, User, Course , Profile};

$user = User::find(250);
$user->load('enrollments');
