<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Slot extends Model
{
    protected $fillable = ['shift_id', 'start_date', 'start_time', 'end_time'];

    // Slots belong to a shift
    public function shift()
    {
        return $this->belongsTo('App\Models\Shift');
    }

    // Slots can also belong to a user
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // Convenience for getting the department of a slot
    public function getDepartmentAttribute()
    {
        return $this->shift->department;
    }

    // Convenience for getting the event of a slot
    public function getEventAttribute()
    {
        return $this->shift->event;
    }
    
    // Helper function to get the number of seconds in a timestamp
    static public function timeToSeconds($time)
    {
        $parsed = date_parse_from_format('H:i', $time);
        $seconds = 0;

        $seconds += $parsed['hour'] * 60 * 60;
        $seconds += $parsed['minute'] * 60;

        return $seconds;
    }

    // Helper function to convert seconds back into hours
    static public function secondsToTime($seconds)
    {
        return date('H:i', $seconds);
    }
    
    // Helper function to generate slots based on shift information
    static public function generate($schedule)
    {
        // Delete all existing slots for this shift
        Slot::where('shift_id', $schedule->id)->delete();

        // Set up required variables
        $end_date = new Carbon($schedule->end_date);
        $dates = json_decode($schedule->getAttribute('dates'));
        $volunteers = (int)$schedule->volunteers;

        // Generate slots for each requested volunteer
        while($volunteers > 0)
        {
            $date = new Carbon($schedule->start_date);

            // Loop over all days between the start and end date
            while($date->lte($end_date))
            {
                // Only create slots if the current date exists in the list of selected dates
                if(in_array($date->format('Y-m-d'), $dates))
                {
                    // Convert shift times to seconds
                    $start = Slot::timeToSeconds($schedule->start_time);
                    $end = Slot::timeToSeconds($schedule->end_time);
                    $duration = Slot::timeToSeconds($schedule->duration);

                    // Now loop over the times based on the slot duration
                    for($time = $start; $time + $duration <= $end; $time += $duration)
                    {
                        $slot =
                        [
                            'shift_id' => $schedule->id,
                            'start_date' => $date->format('Y-m-d'),
                            'start_time' => Slot::secondsToTime($time),
                            'end_time' => Slot::secondsToTime($time + $duration),
                        ];

                        Slot::create($slot);
                    }
                }

                // All done? Move onto the next day
                $date->addDay();
            }

            $volunteers--;
        }
    }
}

