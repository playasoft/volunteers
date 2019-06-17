<?php

use Faker\Generator as Faker;
use App\Models\Slot;
use App\Models\Schedule;

$factory->define(Slot::class, function (Faker $faker) {
    return [
        'start_date' => $faker->dateTimeThisYear->format('Y-m-d'),
        'start_time'=> $faker->time('H:i'),
        'end_time' => $faker->time('H:i'),
        'row' => 3
    ];
});

$factory->state(Slot::class, 'with-setup',function (Faker $faker) {
    return [
        'schedule_id' => factory(Schedule::class)->states('with-setup')->create()->id
    ];
});
