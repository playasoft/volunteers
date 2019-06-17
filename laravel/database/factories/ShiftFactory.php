<?php

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Event;
use App\Models\Department;

$factory->define(Shift::class, function (Faker\Generator $faker)
{
    return
    [
        'name' => $faker->jobTitle,
        'description' => $faker->bs
    ];
});

$factory->state(Shift::class, 'with-setup', function (Faker\Generator $faker)
{
    return
    [
        'department_id' => function() {
            return factory(Department::class)->states('with-setup')->create()->id;
        },
        'event_id' => function($shift) {
            return Department::find($shift['department_id'])->event->id;
        }
    ];
});
