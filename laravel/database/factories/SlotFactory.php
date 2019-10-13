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
        'start_time' => function($slot) use ($data, $duration) {
            if(isset($data['end_time']))
            {
                
            }

            

            Carbon::createFromTime($faker->numberBetween(0, 23))->format('H:i:s');
        },
        'end_time' => function($slot) use ($faker, $duration_min, $duration_max)
        {
            $duration = $faker->numberBetween($duration_min, $duration_max);
            $start_time = Carbon::createFromFormat('H:i:s', $slot['start_time']);
            $end_time = $start_time->addHours($duration);
            return $end_time->format('H:i:s');
        },
        'row' => 1,
        'schedule_id' => function ($slot) use ($data)
        {
            $schedule_subset = Helpers::subsetArray($data, [
                'start_date', 
                'start_time', 
                'end_time', 
                'event_id'
            ]);
            return factory(Schedule::class)->create($schedule_subset)->id;
        },
        'event_id' => function ($slot)
        {
            return Schedule::find($slot['schedule_id'])->event->id;
        },
    ];
});
