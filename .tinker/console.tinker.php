<?php #Tinker
use App\Models\{Enrollment, User, Course , Profile};

//$profile = Profile::find(1);
//$profile->delete();

//$profile = Profile::find(1); # get profile with id 1
//$profile->forceDelete(); # permanently delete the profile
//
//$profiles = Profile::withTrashed()->get(); // get all profiles including soft deleted ones
//
//
//$profile = Profile::onlyTrashed()->find(1); # get only soft deleted profile with id 1
//
//if ($profile && $profile->trashed()) { # check if profile is soft deleted
//    $profile->restore(); // restore the soft deleted profile
//}

$user = User::find(1);
//$user->delete();

//$user = User::onlyTrashed()->find(1); # get only soft deleted user with id 1
//if ($user && $user->trashed()) { # check if user is soft deleted
//    $user->restore(); // restore the soft deleted user
//}
