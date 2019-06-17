<?php

use App\Models\Event;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker)
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

$factory->state(Event::class, 'test', function (Faker $faker)
{
    return
        [
    ];
});
