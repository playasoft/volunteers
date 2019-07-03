<?php

use App\Models\Event;
use App\Models\EventRole;
use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(EventRole::class, function (Faker $faker, array $data)
{
    if(env('APP_DEBUG') && !isset($data['role_id']))
    {
        Log::warning("Using Factory[EventRole] without setting role_id");
    }

    if(env('APP_DEBUG') && !isset($data['event_id']))
    {
        Log::warning("Using Factory[EventRole] without setting event_id");
    }

    return
    [
        'foreign_id' => 0,
        'foreign_type' => '',
        'role_id' => function ()
        {
            return factory(Role::class)->create()->id;
        },
        'event_id' => function ()
        {
            return factory(Event::class)->create()->id;
        },
    ];
});
