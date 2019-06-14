<?php

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Event;
use App\Models\Department;

$factory->define(Shift::class, function (Faker\Generator $faker)
{
    return
    [
        'event_id' => Event::all()->map->id->random(),
        'department_id' => Department::all()->map->id->random(),
        'name' => $faker->jobTitle,
        'description' => $faker->bs
    ];
});
