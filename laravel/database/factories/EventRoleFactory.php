<?php

use App\Models\Event;
use App\Models\EventRole;
use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(EventRole::class, function (Faker $faker)
{
    return [
        'foreign_id' => 0,
        'foreign_type' => '',
    ];
});

$factory->state(EventRole::class, 'test', function (Faker $faker)
{
    return [
        'role_id' => function ()
        {
            return factory(Role::class)->states('test')->create()->id;
        },
        'event_id' => function ()
        {
            return factory(Event::class)->states('test')->create()->id;
        },
    ];
});
