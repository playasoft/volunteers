<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;
    protected $table = 'schedule';
    protected $fillable = ['department_id', 'shift_id', 'start_date', 'end_date', 'dates', 'start_time', 'end_time', 'duration', 'volunteers', 'password'];

    // Schedules belong to a shift
    public function shift()
    {
        return $this->belongsTo('App\Models\Shift');
    }

    // Schedules belong to a department
    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    // Schedules have slots
    public function slots()
    {
        return $this->hasMany('App\Models\Slot');
    }

    // Convenience for getting the event of a scheduled shift
    public function getEventAttribute()
    {
        return $this->department->event;
    }

    // Shedules have roles
    public function roles()
    {
        return $this->morphMany('App\Models\EventRole', 'foreign');
    }

    // Convenience function for getting the current roles or the parent shift's roles
    public function getRoles()
    {
        if(count($this->roles))
        {
            return $this->roles;
        }

        return $this->shift->roles;
    }

    // Convenience function to get the current role names
    public function getRoleNames($options = [])
    {
        $roleNames = [];

        foreach($this->roles as $role)
        {
            // Check if a formatting option was passed
            if(isset($options['format']) && function_exists($options['format']))
            {
                $roleNames[] = call_user_func($options['format'], $role->role->name);
            }
            else
            {
                $roleNames[] = $role->role->name;
            }
        }

        return $roleNames;
    }

    // Helper function to check if a start / end date needs to be set
    static public function setDates($department, $input)
    {
        if(empty($input['end_date']))
        {
            // When the end date is empty, but the start isn't, use the start as the end
            if(!empty($input['start_date']))
            {
                $input['end_date'] = $input['start_date'];
            }
            else
            {
                // Otherwise, use the event end date
                $input['end_date'] = $department->event->end_date;
            }
        }

        if(empty($input['start_date']))
        {
            $input['start_date'] = $department->event->start_date;
        }

        return $input;
    }

    // Function which returns a parsed date regardless of 12 or 24 hour times
    static private function getTime($value)
    {
        $value = trim($value);

        $twelve = date_parse_from_format('h:i a', $value);
        $twentyfour = date_parse_from_format('H:i', $value);

        if(!$twelve['error_count'])
        {
            return $twelve;
        }
        elseif(!$twentyfour['error_count'])
        {
            return $twentyfour;
        }
        else
        {
            return false;
        }
    }

    // Helper function to make sure timestamps are properly formatted when saving shifts
    static public function setTimes($input)
    {
        $start = Schedule::getTime($input['start_time']);
        $end = Schedule::getTime($input['end_time']);

        // Return original input if there was an error parsing timestamps
        if(!$start || !$end)
        {
            return $input;
        }

        $input['start_time'] = $start['hour'] . ":" . str_pad($start['minute'], 2, 0, STR_PAD_LEFT);

        // Convert end time to 24 if "12:00 AM" is used
        if($end['hour'] === 0 && $end['minute'] === 0)
        {
            $input['end_time'] == "24:00";
        }
        else
        {
            $input['end_time'] = $end['hour'] . ":" . str_pad($end['minute'], 2, 0, STR_PAD_LEFT);
        }

        return $input;
    }
    
    // Helper function to format timestamps before displaying them in forms
    public function formatTimes()
    {
        $start = date_parse_from_format('H:i', $this->start_time);
        $end = date_parse_from_format('H:i', $this->end_time);
        $duration = date_parse_from_format('H:i', $this->duration);
        
        $this->start_time = $this->formatStartHour($start['hour']) . ":" . str_pad($start['minute'], 2, 0, STR_PAD_LEFT);
        $this->end_time = $end['hour'] . ":" . str_pad($end['minute'], 2, 0, STR_PAD_LEFT);
        $this->duration = $this->formatDurationHour($duration['hour']) . ":" . str_pad($duration['minute'], 2, 0, STR_PAD_LEFT);
    }

    // this handles a corner case where validation requires times formatted HH:MM but 
    // javascript is detecting custom values based on Carbon formatted times
    // This causes custom values for start_time to be clobbered by the form population
    // when the Schedule edit view is loaded or validation to reject custom values for 
    // Duration because they're formatted as H:MM for single digit values.
    // If we zero pad the hour, custom values are fine, but pre-populated values (6 AM)
    // are detected as a custom value. So, we have to zero pad hour values under 10, 
    // exempting 6 and 9. Except that duration's custom values that would be affected
    // are 3, and 6. So we split them into two functions. Good grief.
    private function formatStartHour($h) 
    {
        if ( (!in_array($h, [6,9]) ) and ($h < 10) ) 
        {
            return str_pad($h, 2, 0, STR_PAD_LEFT);
        }
        else 
        {
            return $h;
        }
    }

    private function formatDurationHour($h) 
    {
        if ( (!in_array($h, [3,6]) ) and ($h < 10) ) 
        {
            return str_pad($h, 2, 0, STR_PAD_LEFT);
        }
        else 
        {
            return $h;
        }
    }
}
