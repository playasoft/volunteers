<?php

use App\Models\Department;
use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Department::class, function (Faker $faker)
{
    return
    [
        'name' => $faker->company,
        'description' => $faker->bs,
    ];
});

$factory->state(Department::class, 'with_setup', function (Faker $faker)
{
    return
    [
        'event_id' => function ()
        {
            return factory(Event::class)->states('with_setup')->create()->id;
        },
    ];
});
