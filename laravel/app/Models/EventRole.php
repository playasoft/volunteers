<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRole extends Model
{
    protected $fillable = ['role_id', 'event_id', 'foreign_id', 'foreign_type'];

    // Event roles belong to a role
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    // Event roles belong to a event
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    // Event roles might be associated with a foreign table
    public function foreign()
    {
        return $this->morphTo();
    }
}
