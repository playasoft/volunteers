<?php

namespace App\Listeners;

use Mail;
use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;

class SendAdminWelcome
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Send a user registered email to all admins
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user = $event->user;

        // TODO: Options to choose which admins to notify
        $admin = User::where('id', 1)->first();

        Mail::send('emails/admin-welcome', compact('user'), function ($message) use ($admin)
        {
            $message->to($admin->email, $admin->name)->subject('New user registered!');
        });
    }
}
