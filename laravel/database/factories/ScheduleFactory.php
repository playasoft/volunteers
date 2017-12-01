<?php

use Carbon\Carbon;
use \App\Models\Schedule;

$factory->define(Schedule::class, function (Faker\Generator $faker, $data) {
    static $department_id;
    static $shift_id;
    $dates = json_decode($data['dates'], true);
    return [
        'department_id' => 1,
        'shift_id' => 1,
        'start_date' => $dates[0],
        'end_date' => end($dates),
        'start_time' => $start = Carbon::createFromFormat('H', $faker->numberBetween(0,12)),
        'end_time' => $end = Carbon::createFromFormat('H', $faker->numberBetween(13,24)),
        'duration' => Carbon::createFromFormat('H', $start->diffInhours($end)),
        'volunteers' => $faker->numberBetween(1, 10),
      
    ];
});
