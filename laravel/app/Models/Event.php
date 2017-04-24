<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'featured'];

    // Helper functions to select events by date
    public static function future($featured)
    {
        if($featured)
        {
            return Event::where('start_date', '>', Carbon::now())
                            ->where('featured', true)
                            ->orderBy('start_date', 'asc')->get();
        }

        return Event::where('start_date', '>', Carbon::now())
                        ->orderBy('start_date', 'asc')->get();
    }

    public static function present($featured)
    {
        if($featured)
        {
            return Event::where('start_date', '<', Carbon::now())
                            ->where('end_date', '>', Carbon::now())
                            ->where('featured', true)
                            ->orderBy('start_date', 'desc')->get();
        }

        return Event::where('start_date', '<', Carbon::now())
                        ->where('end_date', '>', Carbon::now())
                        ->orderBy('start_date', 'desc')->get();
    }

    public static function past($featured)
    {
        if($featured)
        {
            return Event::where('end_date', '<', Carbon::now())
                            ->where('featured', true)
                            ->orderBy('start_date', 'desc')->get();
        }

        return Event::where('end_date', '<', Carbon::now())
                        ->orderBy('start_date', 'desc')->get();
    }

    // Helper function to return the most recent ongoing or upcoming event
    public static function ongoingOrUpcoming($preferFeatured = true)
    {
        $ongoing = Event::present($preferFeatured)->first();

        if(!empty($ongoing))
        {
            return $ongoing;
        }

        $upcoming = Event::future($preferFeatured)->first();

        if(!empty($upcoming))
        {
            return $upcoming;
        }

        // Are there no featured events? Fall back to regular events
        if($preferFeatured)
        {
            return self::ongoingOrUpcoming(false);
        }

        return false;
    }

    // Events have departments
    public function departments()
    {
        return $this->hasMany('App\Models\Department');
    }

    // Events have shifts
    public function shifts()
    {
        return $this->hasMany('App\Models\Shift');
    }

    // Helper function to generate a list of days the event will take place
    public function days()
    {
        // Use carbon!
        $start_date = new Carbon($this->start_date);
        $end_date = new Carbon($this->end_date);
        
        // Array for output
        $days = [];
        
        // This only works when the start date is before the end date
        if($start_date->lte($end_date))
        {
            // $date keeps track of the current date as we loop towards the end
            $date = $start_date;

            while($date->lte($end_date))
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
