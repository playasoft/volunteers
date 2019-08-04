<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Mail;

class SendUserMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Send a batch email to each user with pending email notifications
     *
     * @return void
     */
    public function handle()
    {
        // Group all notification by the user they're sent to
        $user_notifications = Notification::where('type', 'email')
                                ->where('status', 'new')
                                ->get()->groupBy('user_to');

        //send batched notifications to each user via email
        foreach ($user_notifications as $user_id => $notifications)
        {
            $user = User::find($user_id);

            //grab all notification metadata with the layout stored in them
            $notification_metadata = $notifications->map(function($notification) {
                $metadata = $notification->metadata; //copy
                $metadata['layout'] = $notification->layout;
                return $metadata;
            });

            Log::info("Sending daily email to user: {$user->email}");
            Mail::send('emails/user-daily-digest', compact('notification_metadata'), function ($message) use ($user)
            {
                $message->to($user->email, $user->name)->subject('Daily Volunteer Digest - Some things you may want to look over...');
            });

            // Update all notifications to sent
            $notification_ids = $notifications->pluck('id');
            Notification::whereIn('id', $notification_ids)->update(['status' => 'sent']);
        }
    }
}
