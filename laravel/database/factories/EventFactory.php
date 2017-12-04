<?php

use Carbon\Carbon;
use App\Models\Event;

$factory->define(Event::class, function (Faker\Generator $faker, $data) {
    $start_date = (isset($data['start_date']) ? $data['start_date'] : Carbon::now());
    return [
        'name' => $faker->sentence(2),
        'description' => $faker->paragraph(),
        'image' => '',
        'start_date' => $start_date,
        'end_date' => $start_date->copy()->addWeek()
    ];
});
