<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $table = 'user_data';
    protected $fillable = ['burner_name', 'full_name', 'birthday', 'phone', 'emergency_name', 'emergency_phone', 'camp'];
    
    // User data belongs to a user
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
