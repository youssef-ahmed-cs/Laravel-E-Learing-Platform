<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->password = bcrypt($user->password);
        $user->bio = Str::limit($user->bio, 50);
        $user->email_verified_at = $user->email_verified_at ?? now();
    }

    public function updated(User $user): void
    {
        $user->bio = Str::limit($user->bio, 50);
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->save();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {

    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

/*
 * DB::table('users')->insert(['name'=>'Ahmed','email'=>'ahmed@gmail.com','username'=>'ahmed12','role'=>'student','bio'=> 'sdfsdfsdf sdfsd s
dfsdfsd sdft erter  wewe yrt ryrty ','phone'=>'3535335562'])
 * */
