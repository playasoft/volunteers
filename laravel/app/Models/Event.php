<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date'];
    
    public function getDates()
    {
        return array('created_at', 'updated_at', 'deleted_at', 'start_date', 'end_date');
    }
}
