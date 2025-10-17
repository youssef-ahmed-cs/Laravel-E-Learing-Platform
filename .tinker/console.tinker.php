<?php

use App\Models\{Enrollment, Task, User, Course, Profile};

//$user = User::with('courses:title,id,instructor_id')->find(1);
$user = User::with('courses')->find(1);
//$enrollments = Enrollment::with('course:id,title')->where('user_id', 111)->get();
