<?php

use App\Models\Department;
use App\Models\Event;

$factory->define(Department::class, function (Faker\Generator $faker)
{
    return
    [
        'event_id' => Event::all()->map->id->random(),
        'name' => $faker->company,
        'description' => $faker->bs
    ];
});
