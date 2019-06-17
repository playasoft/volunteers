<?php

use App\Models\Department;
use App\Models\Event;
use App\Models\Shift;
use Faker\Generator as Faker;

$factory->define(Shift::class, function (Faker $faker)
{
    return [
        'name' => $faker->jobTitle,
        'description' => $faker->bs,
    ];
});

$factory->state(Shift::class, 'test', function (Faker $faker)
{
    return [
        'department_id' => function ()
        {
            return factory(Department::class)->states('test')->create()->id;
        },
        'event_id' => function ($shift)
        {
            return Department::find($shift['department_id'])->event->id;
        },
    ];
});
