<?php

use App\Models\Department;
use App\Models\Event;

$factory->define(Department::class, function (Faker\Generator $faker)
{
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
