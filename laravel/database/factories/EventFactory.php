<?php

use Carbon\Carbon;
use \App\Models\Event;

$factory->define(Event::class, function (Faker\Generator $faker) {
    static $start;
    return [
        'name' => $faker->sentence(2),
        'description' => $faker->paragraph(),
        'image' => '',
        'start_date' => $start ?: $start = Carbon::now(),
        'end_date' => $start->addWeeks(1)
    ];
});
