<?php

use App\Models\Event;
use App\Models\EventRole;
use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(EventRole::class, function (Faker $faker)
{
    return
    [
        'foreign_id' => 0,
        'foreign_type' => '',
    ];
});

$factory->state(EventRole::class, 'with_setup', function (Faker $faker)
{
    return
    [
        'role_id' => function ()
        {
            return factory(Role::class)->states('with_setup')->create()->id;
        },
        'event_id' => function ()
        {
            return factory(Event::class)->states('with_setup')->create()->id;
        },
    ];
});
