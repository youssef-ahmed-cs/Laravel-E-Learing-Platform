<?php #Tinker
use App\Models\{Enrollment, User, Course , Profile};

$user = User::findOrFail(318);
$user->forceDelete(); // permanently delete the user
