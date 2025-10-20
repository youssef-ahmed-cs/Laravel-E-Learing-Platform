<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserRegisteredEvent
{
    use Dispatchable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
