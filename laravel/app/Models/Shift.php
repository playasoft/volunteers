<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    protected $fillable = ['department_id', 'name', 'start', 'end', 'duration', 'roles'];
    
    // Shifts belong to an department
    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    // Convenience for getting the event of a shift
    public function event()
    {
//        return $this->deparment->event;
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
