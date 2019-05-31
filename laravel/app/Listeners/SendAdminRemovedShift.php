<?php

namespace App\Listeners;

use Mail;
use App\Events\SlotChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminRemovedShift
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
     * Send shift removal email to volunteer
     *
     * @param  SlotChanged  $event
     * @return void
     */
    public function handle(SlotChanged $event)
    {
        if(isset($event->change['admin_released']))
        {
            if($event->change['status'] === 'released' && $event->change['admin_released'] === true)
            {
              $slot = $event->slot;
              $user_email = $event->slot->user->email;
              $user_name = $event->slot->user->name;
              $shift_name = $event->slot->schedule->shift->name;

              $event_data = compact('slot', 'user_email', 'user_name', 'shift_name');

              Mail::send('emails/admin-removed-shift', $event_data, function ($message) use ($user_email, $user_name)
              {
                  $message->to($user_email, $user_name)->subject('Shift reschedule required!');
              });
            }
        }
    }
}
