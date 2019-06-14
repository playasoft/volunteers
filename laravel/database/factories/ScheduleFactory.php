<?php

use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Department;
use App\Models\Shift;


$factory->define(Schedule::class, function (Faker\Generator $faker, $data)
{
    return
    [
        'department_id' => Department::all()->map->id->random(),
        'shift_id' => Shift::all()->map->id->random(),
        'start_date' => $faker->dateTime->format('Y-m-d'),
        'end_date' => $faker->dateTime->modify('+30 day')->format('Y-m-d'),
        'start_time' => Carbon::createFromFormat('H', $faker->numberBetween(0,10))->toTimeString(),
        'end_time' => Carbon::createFromFormat('H', $faker->numberBetween(14,23))->toTimeString(),
        'duration' => Carbon::createFromFormat('H', $faker->numberBetween(2,4))->toTimeString(),
        'volunteers' => $faker->numberBetween(1, 3)
    ];
});
