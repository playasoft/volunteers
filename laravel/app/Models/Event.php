<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Event extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'description', 'start_date', 'end_date'];
    
    public function getDates()
    {
        return array('created_at', 'updated_at', 'deleted_at', 'start_date', 'end_date', 'deleted_at');
    }

    // Helper functions to select events by date
    public function future()
    {
        return $this->where('start_date', '>', Carbon::now())
                    ->orderBy('start_date', 'desc')->get();
    }

    public function present()
    {
        return $this->where('start_date', '<', Carbon::now())
                    ->where('end_date', '>', Carbon::now())
                    ->orderBy('start_date', 'desc')->get();
    }

    public function past()
    {
        return $this->where('end_date', '<', Carbon::now())
                    ->orderBy('start_date', 'desc')->get();
    }
}
