<?php

use App\Models\{Enrollment, Task, User, Course, Profile};

$user = User::find(1);
echo URL::signedRoute('users.show02' , $user);
