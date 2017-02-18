<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shift;
use Carbon\Carbon;

class PopulateScheduleDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:schedule:dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A helper command which populates new schedule columns based on legacy data';

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
        // Loop through all scheduled shifts
        $scheduledShifts = Shift::get();

        foreach($scheduledShifts as $schedule)
        {
            if(empty($schedule->dates))
            {
                echo "Schedule {$schedule->id} missing dates.\n";

                $end_date = new Carbon($schedule->end_date);
                $date = new Carbon($schedule->start_date);
                $dates = [];

                // Loop over all days between the start and end date
                while($date->lte($end_date))
                {
                    $dates[] = $date->format('Y-m-d');
                    $date->addDay();
                }

                $schedule->dates = json_encode($dates);
            }

            if(empty($schedule->volunteers))
            {
                echo "Schedule {$schedule->id} missing volunteers.\n";

                $schedule->volunteers = 1;
            }

            $schedule->save();
            echo "------------------------------------------\n";
        }
    }
}
