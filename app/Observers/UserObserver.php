<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
    	$user->configs()->delete();
    }
}
