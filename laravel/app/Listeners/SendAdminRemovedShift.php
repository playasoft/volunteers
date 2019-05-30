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
      if(isset($event->change) && isset($event->change['status'])) {
        if($event->change['status'] === 'released') {
          $slot = $event->slot;
          Mail::send('emails/admin-removed-shift', compact('slot'), function ($message) use ($slot)
          {
              $message->to($slot->user->email, $slot->user->name)->subject('Shift reschedule required!');
          });
        }
      }
    }
}
