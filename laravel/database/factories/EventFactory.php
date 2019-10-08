<?php

use App\Models\Event;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker, array $data)
{
    return
    [
        'name' => $faker->unique()->sentence(2),
        'description' => $faker->paragraph(),
        'image' => '', //empty path
        'start_date' => function()
        {
            return Carbon::tomorrow()->format('Y-m-d');
        },
        'end_date' => function($event) use ($faker)
        {
            $start_date = $event['start_date'];
            if(is_string($event['start_date']))
            {
                $start_date = Carbon::parse($event['start_date']);
            }

            $extra_days = $faker->numberBetween(3,7);
            return $start_date->addDays($extra_days)->format('Y-m-d');
        },
    ];
});
