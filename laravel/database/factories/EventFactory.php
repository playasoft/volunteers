<?php

use Carbon\Carbon;
use \App\Models\Event;

$factory->define(Event::class, function (Faker\Generator $faker) {
    static $image;
    return [
        'name' => $faker->sentence(2),
        'description' => $faker->paragraph(),
        'image' => $image ?: $image = '',
        'start_date' => Carbon::now(),
        'end_date' => Carbon::now()->addWeeks(1)
    ];
});
