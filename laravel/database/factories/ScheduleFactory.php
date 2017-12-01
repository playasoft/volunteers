<?php

use Carbon\Carbon;
use \App\Models\Schedule;

$factory->define(Schedule::class, function (Faker\Generator $faker, $data) {
    static $department_id;
    static $shift_id;
    $dates = json_decode($data['dates'], true);
    return [
        'department_id' => $department_id ?: $department_id = 1,
        'shift_id' => $shift_id ?: $shift_id = 1,
        'start_date' => $dates[0],
        'end_date' => end($dates),
        'volunteers' => $faker->numberBetween(1, 10),
      
    ];
});
