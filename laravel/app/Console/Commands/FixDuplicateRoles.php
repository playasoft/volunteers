<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRole;
use App\Models\EventRole;

class FixDuplicateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:role:duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A helper command to remove duplicate roles from users and events';

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
        // Select all roles
        $userRoles = UserRole::get();
        $eventRoles = EventRole::get();

        // Loop through roles to remove duplicates
        foreach($userRoles as $userRole)
        {
            // Make sure this role still exists
            if(empty(UserRole::find($userRole->id)))
            {
                continue;
            }

            UserRole::where('user_id', $userRole->user_id)->where('role_id', $userRole->role_id)->where('id', '!=', $userRole->id)->delete();
        }

        foreach($eventRoles as $eventRole)
        {
            if(empty(EventRole::find($eventRole->id)))
            {
                continue;
            }

            EventRole::where('event_id', $eventRole->event_id)->where('role_id', $eventRole->role_id)->where('id', '!=', $eventRole->id)->delete();
        }

        dump("Duplicate roles removed.");
    }
}
