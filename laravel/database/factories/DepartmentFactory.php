<?php

use App\Models\Department;
use App\Models\Event;

$factory->define(Department::class, function (Faker\Generator $faker)
{
    return
        [
        'name' => $faker->company,
        'description' => $faker->bs,
    ];
});

$factory->state(Department::class, 'with-setup', function (Faker\Generator $faker)
{
    return
        [
        'event_id' => function ()
        {
            return factory(Event::class)->states('with-setup')->create()->id;
        },
    ];
});
