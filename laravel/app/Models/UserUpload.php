<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUpload extends Model
{
    // Uploads belong to a user
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
