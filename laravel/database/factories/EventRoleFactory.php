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

$factory->state(EventRole::class, 'with-setup', function (Faker $faker)
{
    return [
        'role_id' => factory(Role::class)->states('with-setup')->create()->id,
        'event_id' => factory(Event::class)->states('with-setup')->create()->id,
    ];
});
