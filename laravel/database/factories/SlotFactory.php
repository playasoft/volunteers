<?php

use App\Models\Schedule;
use App\Models\Slot;
use Faker\Generator as Faker;

$factory->define(Slot::class, function (Faker $faker)
{
    $row_min = 1;
    $row_max = 3;

    return [
        'start_date' => $faker->dateTimeThisYear->format('Y-m-d'),
        'start_time' => $faker->time('H:i'),
        'end_time' => $faker->time('H:i'),
        'row' => $faker->numberBetween($row_min, $row_max),
    ];
});

$factory->state(Slot::class, 'test', function (Faker $faker)
{
    return [
        'schedule_id' => function ()
        {
            return factory(Schedule::class)->states('test')->create()->id;
        },
    ];
});
