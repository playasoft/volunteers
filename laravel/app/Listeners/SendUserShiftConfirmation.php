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
     * Send a user a confimation email for taken/assigned shift
     *
     * @param  SlotChanged  $event
     * @return void
     */
    public function handle(SlotChanged $event)
    {
        if ($event->change['status'] === 'taken')
        {
            $slot = $event->slot;
            $user_email = $event->change['email'];
            $user_name = $event->change['name'];
            $event_name = $event->slot->schedule->shift->event->name;
            $shift_name = $event->slot->schedule->shift->name;
            $start_date = date('l M d',strtotime($event->slot->start_date));
            $start_time = date('h:i a',strtotime($event->slot->start_time . $event->slot->start_date));
            $end_time = date('h:i a',strtotime($event->slot->end_time . $event->slot->start_date));

            $admin_assigned = false;
            if (isset($event->change['admin_assigned']))
            {
                $admin_assigned = $event->change['admin_assigned'];
            }

            $event_data = compact('slot', 'user_email', 'user_name', 'event_name', 'shift_name', 'start_date', 'start_time', 'end_time', 'admin_assigned');

            try
            {
                Mail::send('emails/user-shift-confirmation', $event_data, function ($message) use ($user_email, $user_name, $shift_name)
                {
                    $message->to($user_email, $user_name)->subject('Confirmation Email - ' . $shift_name . ' shift!');
                });
            }
            catch (\Exception $exception)
            {
                app('request')->session()->flash('warning', "Unable to send email confirmation, SMTP error. Please notify the administrator of this volunteer database.");
            }
        }
    }
}
