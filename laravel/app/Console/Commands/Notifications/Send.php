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

    // Map of notification events and their handler functions
    private $eventHandler =
    [
        'SlotCreated' => 'dailyDigest',
        'SlotUpdated' => 'dailyDigest'
    ];

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
            $notifications = Notification::where('user_to', $user_id)->where('type', 'email')->where('status', 'new')->get();
            // $this->dispatchToHandler($user, $notifications); //NOTE: re-enable once you have more than slots to write about
            $this->dailyDigest($user, $notifications);
        }
    }

    //Helper function which dispatches notifications to their handler functions
    private function dispatchToHandler($user, $notifications)
    {
        $queue = [];

        // Determine which event handler should handle these notifications
        foreach($notifications as $notification)
        {
            $metadata = json_decode($notification->metadata);

            if(isset($this->eventHandler[$metadata->event]))
            {
                $handler = $this->eventHandler[$metadata->event];

                if(!isset($queue[$handler]))
                {
                    $queue[$handler] = [];
                }

                $queue[$handler][] = $notification;
            }
        }

        // Now loop through the queue and call the handler functions
        foreach($queue as $function => $data)
        {
            $this->{$function}($user, $data);
        }
    }

    private function dailyDigest($user, $notifications)
    {
        $notification_ids = [];
        $slot_ids = [];
        $slot_metadata = [];

        // Loop through all notifications and find the associated slot
        foreach($notifications as $notification)
        {
            $metadata = json_decode($notification->metadata);
            $slot = Slot::find($metadata->slot_id);

            // Prevent displaying older copies of the same slot (question asked and user responded on the same day)
            if(in_array($slot->id, $slot_ids))
            {
                $other_slot = Slot::find($slot_ids[$slot->id]);
                if($slot->created_at->lt($other_slot)) //if newer than other slot
                {
                    continue;
                }
            }

            $notification_ids[] = $notification->id;
            $slot_ids[] = $slot->id;
            $slot_metadata[] = $metadata;
        }

        echo "Sending daily email to user: {$user->email}\n";
        Mail::send('emails/user-daily-digest', compact('slot_metadata'), function ($message) use ($user)
        {
            $message->to($user->email, $user->name)->subject('Daily Volunteer Digest - Some things you may want to look over...');
        });

        // Update all notifications to sent
        Notification::whereIn('id', $notification_ids)->update(['status' => 'sent']);
    }
}
