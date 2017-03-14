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

    // Convenience function to get the current role names
    public function getRoleNames()
    {
        $roleNames = [];

        foreach($this->roles as $role)
        {
            $roleNames = $role->role->name;
        }

        return $roleNames;
    }
}
