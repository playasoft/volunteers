<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = ['event_id', 'name', 'description', 'roles'];

    // Departments belong to an event
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    // Departments have shifts
    public function shifts()
    {
        return $this->hasMany('App\Models\Shift');
    }

    // Departments have slots through shifts
    public function slots()
    {
        return $this->hasManyThrough('App\Models\Slot', 'App\Models\Shift');
    }
}
