<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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


class remindUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs through the slots table to determine which shifts are happening within the hour.';

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

         $currDate = date("Y-m-d H:i:s");

         $currTimePlusOneHour = date('H:i:s', time() + 3600);

         $currTimePlusTwoHours = date('H:i:s', time() + 7200);

         $currTimePlusThreeHours = date('H:i:s', time() + 10800);

         $shifts = Slot::get();

         // Cycle through all the slots
         foreach($shifts as $shift)
         {

             // Find all the shifts that start today
             if($shift->start_date == date('Y-m-d'))
             {

                 // Find all the shifts that start within the next hour
                 if($shift->start_time >= $currTimePlusOneHour and $shift->start_time > date('H:i:s'))
                 {

                     // Find all the shifts that are empty
                     if(empty($shift->user_id))
                     {
                         echo 'No one has signed up for it, so you should send a reminder to the admin'.PHP_EOL;

                         $admin = User::get()->first();

                         // Updating Database isNotified value
                         DB::table('users')
                                     ->where('id', $shift->id)
                                     ->update(['isNotified' => 'Yes']);


                         // Notify admin of unregistered shift
                         if($shift->isNotified == 'No')
                         {
                             $admin->notify(new shiftStarting($shift, $admin));
                         }

                     }

                     // Find all the shifts that are full
                     else
                     {
                         echo $shift->getDepartmentAttribute()->description.PHP_EOL;

                         $users = User::get();

                         // Cycle through users
                         foreach ($users as $user)
                         {

                             // Find user that is registered for this shift
                             if ($user->id == $shift->user_id) {

                                 echo 'You should remind '.$user->email.' that they have a shift starting soon'.PHP_EOL;

                                 // Updating Database isNotified value
                                 DB::table('slots')
                                             ->where('id', $shift->id)
                                             ->update(['isNotified' => 'Yes']);

                                 // Notify user of upcoming shift
                                 if($shift->isNotified == 'No')
                                 {
                                     $user->notify(new shiftStarting($shift, $user));
                                 }

                             }
                         }

                     }
                 }
                 else
                 {
                     echo 'Shift does not start within the hour'.PHP_EOL;
                 }
             }
             else
             {
                 echo 'No shifts begin today'.PHP_EOL;
             }

         }

     }

 }
