<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftData extends Model
{
    protected $table = 'shift_data';

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
}
