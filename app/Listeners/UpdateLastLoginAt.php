<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

class UpdateLastLoginAt
{
    /**
     * Update last_login_at everytime the user logged in
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $user->last_login_at = now();
        $user->save();
    }
}
