<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['event_id', 'department_id', 'name', 'description', 'roles'];

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

    // Convenience function for getting the current roles or the parent department's roles
    public function getRoles()
    {
        if($this->roles)
        {
            return $this->roles;
        }

        return $this->department->roles;
    }
}
