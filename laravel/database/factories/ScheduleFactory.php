<?php

use App\Helpers;
use App\Models\Department;
use App\Models\Event;
use App\Models\Schedule;
use App\Models\Shift;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Schedule::class, function (Faker $faker, array $data)
{
    if (env('APP_DEBUG') && !isset($data['department_id']))
    {
        Log::warning("Using Factory[Schedule] without setting department_id");
    }

    if (env('APP_DEBUG') && !isset($data['shift_id']))
    {
        Log::warning("Using Factory[Schedule] without setting shift_id");
    }

    $duration_min = 2; //hours
    $duration_max = 8; //hours

    $days_min = 2;
    $days_max = 4;

    $volunteer_min = 1;
    $volunteer_max = 3;

    return
    [
        'start_date' => function($schedule) use ($data) {
            $start_date = Carbon::tomorrow()->addDays(1)->format('Y-m-d');
            if(isset($data['event_id'])) {
                $start_date = Event::find($data['event_id'])->start_date;
            }
            return $start_date;
        },
        'end_date' => function($schedule) use ($faker, $days_min, $days_max)
        {
            $duration = $faker->numberBetween($days_min,$days_max);
            $start_date = Carbon::createFromFormat('Y-m-d', $schedule['start_date']);
            $end_date = $start_date->addDays($duration);
            $start_date = Carbon::tomorrow()->addDays(1)->format('Y-m-d');
            if(isset($data['event_id'])) {
                $start_date = Event::find($data['event_id'])->end_date;
            }
            return $end_date->format('Y-m-d');
        },
        'start_time' => Carbon::createFromTime($faker->numberBetween(0, 23))->format('H:i:s'),
        'end_time' => function($schedule) use ($faker, $duration_min, $duration_max)
        {
            $duration = $faker->numberBetween($duration_min, $duration_max);
            $start_time = Carbon::createFromFormat('H:i:s', $schedule['start_time']);
            $end_time = $start_time->addHours($duration);
            return $end_time->format('H:i:s');
        },
        'duration' => function($schedule)
        {
            $start_time = Carbon::createFromFormat('H:i:s', $schedule['start_time']);
            $end_time = Carbon::createFromFormat('H:i:s', $schedule['end_time']);
            $duration = $end_time->diff($start_time);
            return $duration->format('%H:%I:%S');
        },
        'volunteers' => $faker->numberBetween($volunteer_min, $volunteer_max),
        'event_id' => function ($schedule)
        {
            return factory(Event::class)->create([
                'start_date' => $schedule['start_date'],
                'end_date' => $schedule['end_date'],
            ])->id;
        },
        'shift_id' => function ($schedule) use ($data)
        {
            $shift_subset = Helpers::subsetArray($data, [
                'department_id', 
                'event_id',
            ]);
            $shift_subset['event_id'] = $schedule['event_id'];
            return factory(Shift::class)->create($shift_subset)->id;
        },
        'department_id' => function ($schedule)
        {
            return Shift::find($schedule['shift_id'])->department->id;
        },
    ];
});
