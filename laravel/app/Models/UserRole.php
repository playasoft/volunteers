<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $fillable = ['role_id', 'user_id', 'foreign_id', 'foreign_type'];

    // User roles belong to a role
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    // User roles belong to a user
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // User roles might be associated with a foreign table
    public function foreign()
    {
        return $this->morphTo();
    }
}
