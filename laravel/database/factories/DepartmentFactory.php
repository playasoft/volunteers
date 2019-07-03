<?php

use App\Models\Department;
use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Department::class, function (Faker $faker, array $data)
{
    if(env('APP_DEBUG') && !isset($data['event_id']))
    {
        Log::warning("Using Factory[Department] without setting event_id");
    }

    return
    [
        'name' => $faker->company,
        'description' => $faker->bs,
        'event_id' => function ()
        {
            return factory(Event::class)->create()->id;
        },
    ];
});
