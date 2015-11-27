<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    protected $fillable = ['department_id', 'name', 'start_date', 'end_date', 'start_time', 'end_time', 'duration', 'roles'];
    
    // Shifts belong to an department
    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    // Convenience for getting the event of a shift
    public function getEventAttribute()
    {
        return $this->department->event;
    }

    // Convenience function for getting the current roles or the parent department's roles
    public function getRoles()
    {
        if($this->roles)
        {
            return $this->roles;
        }

        return $this->department->roles;
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

    // Helper function to make sure timestamps are properly formatted
    static public function setTimes($input)
    {
        $start = Shift::getTime($input['start_time']);
        $end = Shift::getTime($input['end_time']);

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
}
