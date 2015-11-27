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
}
