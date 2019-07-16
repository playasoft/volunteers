<?php

use App\Models\Schedule;
use App\Models\Slot;
use Faker\Generator as Faker;

$factory->define(Slot::class, function (Faker $faker, array $data)
{
    if(env('APP_DEBUG') && !isset($data['schedule_id']))
    {
        Log::warning("Using Factory[Slot] without setting schedule_id");
    }
    
    return
    [
        'start_date' => $faker->dateTimeThisYear->format('Y-m-d'),
        'start_time' => $faker->time('H:i'),
        'end_time' => $faker->time('H:i'),
        'row' => 1,
        'schedule_id' => function ()
        {
            return factory(Schedule::class)->create()->id;
        },
    ];
});
