<?php

use App\Models\Department;
use App\Models\Schedule;
use App\Models\Shift;
use Carbon\Carbon;

$factory->define(Schedule::class, function (Faker\Generator $faker, $data)
{
    $duration_min = 2; //hours
    $duration_max = 8; //hours

    $volunteer_min = 1;
    $volunteer_max = 3;

    $start_datetime = Carbon::tomorrow();
    $end_datetime = $start_datetime->copy()->addWeeks($faker->randomDigitNotNull);

    $duration = CarbonInterval::hours($faker->numberBetween($duration_min,$duration_max));
    $start_time = Carbon::createFromTime($faker->numberBetween(0,23));
    $end_time = $start_time->addHours($duration);

    return
        [
        'department_id' => Department::all()->map->id->random(), //kill
        'shift_id' => Shift::all()->map->id->random(), //kill
        'start_date' => $start_datetime->format('Y-m-d'),
        'end_date' => $end_datetime->format('Y-m-d'),
        'start_time' => $start_time->format('H:M:S'),
        'end_time' => $end_time->format('H:M:S'),
        'duration' => $duration->format('%H:%M:%S'),
        'volunteers' => $faker->numberBetween($volunteer_min, $volunteer_max),
    ];
});
