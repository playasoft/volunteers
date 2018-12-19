<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['event_id', 'department_id', 'name', 'description'];

    // Shifts belong to an event
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    // Shifts belong to a department
    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    // Shifts have roles
    public function roles()
    {
        return $this->morphMany('App\Models\EventRole', 'foreign');
    }

    // Shifts have a schedule
    public function schedule()
    {
        return $this->hasMany('App\Models\Schedule');
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
}
