<?php

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
        'start_date' => Carbon::tomorrow()->addDays(1)->format('Y-m-d'),
        'end_date' => function($schedule) use ($faker, $days_min, $days_max)
        {
            $duration = $faker->numberBetween($days_min,$days_max);
            $start_date = Carbon::createFromFormat('Y-m-d', $schedule['start_date']);
            $end_date = $start_date->addDays($duration);
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
            return $duration->format('H:i:s');
        },
        'volunteers' => $faker->numberBetween($volunteer_min, $volunteer_max),
        'department_id' => function ($schedule)
        {
            $event_id = function () use ($schedule)
            {
                return factory(Event::class)->create([
                    'start_date' => $schedule['start_date'],
                    'end_date' => $schedule['end_date'],
                ]);
            };
            return factory(Department::class)->create([
                'event_id' => $event_id,
            ])->id;
        },
        'shift_id' => function ($schedule)
        {
            $department = Department::find($schedule['department_id']);
            return factory(Shift::class)->create([
                'department_id' => $department->id,
                'event_id' => $department->event->id,
            ])->id;
        },
    ];
});
