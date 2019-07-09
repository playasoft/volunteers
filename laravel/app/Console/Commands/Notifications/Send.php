<?php

namespace App\Console\Commands\Notifications;

use Illuminate\Console\Command;
use DB;
use Mail;
use App\Models\Notification;
use App\Models\User;
use App\Models\Slot;

class Send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send queued notifications';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all users that have queued notifications
        $users = DB::table('notifications')->select('user_to')->where('type', 'email')->where('status', 'new')->groupBy('user_to')->pluck('user_to');

        foreach($users as $user_id)
        {
            $user = User::find($user_id);

            // Get all notifications for this user
            $notifications = Notification::where('user_to', $user_id)
                                ->where('type', 'email')->where('status', 'new')
                                ->get();
            $this->sendUserBatchMail($user, $notifications);
        }
    }

    //Helper function which dispatches notifications to their users
    private function sendUserBatchMail($user, $notifications)
    {
        $notification_ids = [];
        $notification_metadata = [];

        // store layout in metadata and track notifications that are sent
        foreach($notifications as $notification)
        {
            $layout = $notification->layout;
            $metadata = json_decode($notification->metadata);

            $metadata->layout = $layout;

            $notification_ids[] = $notification->id;
            $notification_metadata[] = (array) $metadata;
        }

        echo "Sending daily email to user: {$user->email}\n";
        Mail::send('emails/user-daily-digest', compact('notification_metadata'), function ($message) use ($user)
        {
            $message->to($user->email, $user->name)->subject('Daily Volunteer Digest - Some things you may want to look over...');
        });

        // Update all notifications to sent
        Notification::whereIn('id', $notification_ids)->update(['status' => 'sent']);
    }
}
