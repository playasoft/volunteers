<?php

namespace App\Console\Commands;

use App\Models\Slot;
use App\Models\User;
use App\Models\Event;
use App\Notifications\EventCreated;
use App\Notifications\shiftStarting;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Notification\Notifications;
use App\Models\UserRole;
use Illuminate\Console\Command;

class sendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A test for the email format.';

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
        $shift = Slot::get()->first();

        $admins = UserRole::get()->where('role_id', 1);

        //echo $admins.PHP_EOL;

        foreach ($admins as $admin)
        {
            $user = User::get()->where('id', $admin->user_id)->first();

            $user->notify(new shiftStarting($shift, $user));
        }



    }
}
