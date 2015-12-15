<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUpload extends Model
{
    protected $fillable = ['name', 'description'];
    
    // Uploads belong to a user
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
