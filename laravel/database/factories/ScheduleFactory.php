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

    $volunteer_min = 1;
    $volunteer_max = 3;

    $start_datetime = Carbon::tomorrow();
    $end_datetime = $start_datetime->copy()->addWeeks($faker->randomDigitNotNull);

    $duration = Carbon::createFromTime($faker->numberBetween($duration_min, $duration_max));
    $start_time = Carbon::createFromTime($faker->numberBetween(0, 23));
    $end_time = $start_time->addHours($duration->hour);

    return
    [
        'start_date' => $start_datetime->format('Y-m-d'),
        'end_date' => $end_datetime->format('Y-m-d'),
        'start_time' => $start_time->format('H:M:S'),
        'end_time' => $end_time->format('H:M:S'),
        'duration' => $duration->format('H:M:S'),
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
