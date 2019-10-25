<?php

use App\Helpers;
use App\Models\Event;
use App\Models\Schedule;
use App\Models\Slot;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Slot::class, function (Faker $faker, array $data)
{
    if(env('APP_DEBUG') && !isset($data['schedule_id']))
    {
        Log::warning("Using Factory[Slot] without setting schedule_id");
    }

    $duration_min = 2; //hours
    $duration_max = 8; //hours

    $duration = $faker->numberBetween($duration_min, $duration_max);

    return
    [
        'start_date' => Carbon::tomorrow()->addDays(1)->format('Y-m-d'),

        'start_time' => function($slot) use ($faker, $data, $duration) {
            //default randomized hour
            $start_hour = $faker->numberBetween(0, 23);

            //set same day hour if end_time has been set
            if(isset($data['end_time']))
            {
                $end_time = Helpers::carbonize($data['end_time']);
                $start_hour = $end_time->hour - $duration;
                if($start_hour < 0) 
                {
                    $start_hour = 0;
                }
            }

            return Carbon::createFromTime($start_hour)->format('H:i:s');
        },

        'end_time' => function($slot) use ($faker, $duration)
        {
            $start_time = Helpers::carbonize($slot['start_time']);
            $end_hour = $start_time->hour + $duration;
            if($end_hour > 23)
            {
                $end_hour = 23;
            }
            return Carbon::createFromTime($end_hour)->format('H:i:s');
        },

        'row' => 1,

        'schedule_id' => function ($slot) use ($faker, $data)
        {
            $schedule_subset = Helpers::subsetArray($data, [
                'event_id',
            ]);
            $schedule_subset['start_date'] = $slot['start_date'];
            $schedule_subset['start_time'] = $slot['start_time'];
            $schedule_subset['end_time'] = $slot['end_time'];
            return factory(Schedule::class)->create($schedule_subset)->id;
        },

        'event_id' => function ($slot)
        {
            return Schedule::find($slot['schedule_id'])->event->id;
        },
    ];
});
