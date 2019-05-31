<?php

namespace App\Listeners;

use Mail;
use App\Events\SlotChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserShiftConfirmation
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
     * Send a user welcome email
     *
     * @param  SlotChanged  $event
     * @return void
     */
    public function handle(SlotChanged $event)
    {
        if (isset($event->change['status'])) {
            if ($event->change['status'] === 'taken') {
                $slot = $event->slot;
                $name = $event->change['name'];
                $email = $event->change['email'];
                $adminAssigned = (isset($event->change['adminAssigned'])) ? $event->change['adminAssigned'] : false;
                Mail::send('emails/user-shift-confirmation', compact('slot','adminAssigned'), function ($message) use ($slot, $name, $email) {
                    $message->to($email, $name)->subject('Confimation Email - ' . $slot->schedule->shift->name . ' shift!');
                });
            }
        }
    }
}
