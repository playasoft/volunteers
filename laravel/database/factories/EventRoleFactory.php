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
        'role_id' => function() {
            return factory(Role::class)->create()->id;
        },
        'event_id' => function() {
            return factory(Event::class)->create()->id;
        },
    ];
});
