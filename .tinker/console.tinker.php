<?php

use App\Models\{Enrollment, Task, User, Course, Profile};

### Tinkerer Console v1.0 ###
//$user = User::firstOrFail();
//$users = User::all();
//$admins = $users->where('role', 'admin');
//$count = User::where('role', 'student')->count();
//$users = User::with(['courses:title,instructor_id'])
//    ->where('id', 1)->get();

$user = User::where('id', '1')
    ->get(['id', 'name', 'email', 'role']);
