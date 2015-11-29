<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Slot extends Model
{
    protected $fillable = ['shift_id', 'start_date', 'start_time', 'duration'];
    
    // Helper function to get the number of seconds in a timestamp
    static private function timeToSeconds($time)
    {
        $parsed = date_parse_from_format('H:i', $time);
        $seconds = 0;

        $seconds += $parsed['hour'] * 60 * 60;
        $seconds += $parsed['minute'] * 60;

        return $seconds;
    }

    // Helper function to convert seconds back into hours
    static private function secondsToTime($seconds)
    {
        return date('H:i', $seconds);
    }
    
    // Helper function to generate slots based on shift information
    static public function generate($shift)
    {
        // Delete all existing slots for this shift
        Slot::where('shift_id', $shift->id)->delete();

        // Loop over shift days
        $date = $shift->start_date;

        while($date <= $shift->end_date)
        {
            // Convert shift times to seconds
            $start = Slot::timeToSeconds($shift->start_time);
            $end = Slot::timeToSeconds($shift->end_time);
            $duration = Slot::timeToSeconds($shift->duration);

            // Now loop over the times based on the slot duration
            for($time = $start; $time < $end; $time += $duration)
            {
                $slot =
                [
                    'shift_id' => $shift->id,
                    'start_date' => $date->format('Y-m-d'),
                    'start_time' => Slot::secondsToTime($time),
                    'duration' => $shift->duration,
                ];
                
                Slot::create($slot);
            }
            
            $date->addDay();
        }
    }
}
