<?php #Tinker
use App\Models\{Enrollment, User, Course , Profile};

$profile = Profile::find(1);
$profile->delete();

//$profile = Profile::find(1);






