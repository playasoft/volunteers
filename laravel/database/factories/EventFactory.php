<?php

use App\Models\Event;
use Carbon\Carbon;

$factory->define(Event::class, function (Faker\Generator $faker)
{
    $start_datetime = Carbon::tomorrow();
    $end_datetime = $start_datetime->copy()->addWeeks($faker->randomDigitNotNull);
    return
        [
        'name' => $faker->unique()->sentence(2),
        'description' => $faker->paragraph(),
        'image' => '', //empty path
        'start_date' => $start_datetime->format('Y-m-d'),
        'end_date' => $end_datetime->format('Y-m-d'),
    ];
});
