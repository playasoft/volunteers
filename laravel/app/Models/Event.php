<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Event extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'description', 'start_date', 'end_date'];
    protected $dates = ['start_date', 'end_date'];

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

    // Events have departments, which in turn have shifts
    public function departments()
    {
        return $this->hasMany('App\Models\Department');
    }

    // Helper function to generate a list of days the event will take place
    public function days()
    {
        // Array for output
        $days = [];
        
        // This only works when the start date is before the end date
        if($this->start_date <= $this->end_date)
        {
            // $date keeps track of the current date as we loop towards the end
            $date = $this->start_date;

            while($date <= $this->end_date)
            {
                $days[] = (object)
                [
                    'name' => $date->formatLocalized('%A'),
                    'date' => clone $date
                ];

                $date->addDay();
            }
        }

        return $days;
    }
}
