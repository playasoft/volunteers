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

    // Helper function to format timestamps before submitting them to the server
    public function formatTimes()
    {
        $start = date_parse_from_format('H:i', $this->start_time);
        $end = date_parse_from_format('H:i', $this->end_time);
        $duration = date_parse_from_format('H:i', $this->duration);

        $this->start_time = str_pad($start['hour'], 2, 0, STR_PAD_LEFT) . ":" . str_pad($start['minute'], 2, 0, STR_PAD_LEFT);
        $this->duration = str_pad($duration['hour'], 2, 0, STR_PAD_LEFT) . ":" . str_pad($duration['minute'], 2, 0, STR_PAD_LEFT);
        $this->end_time = $end['hour'] . ":" . str_pad($end['minute'], 2, 0, STR_PAD_LEFT);
    }
}
