<?php

use App\Models\Department;
use App\Models\Event;
use App\Models\Shift;
use Faker\Generator as Faker;

$factory->define(Shift::class, function (Faker $faker, array $data)
{
    if(env('APP_DEBUG') && !isset($data['department_id']))
    {
        Log::warning("Using Factory[Shift] without setting department_id");
    }

    if(env('APP_DEBUG') && !isset($data['event_id']))
    {
        Log::warning("Using Factory[Shift] without setting event_id");
    }

    return
    [
        'name' => $faker->jobTitle,
        'description' => $faker->bs,
        'department_id' => function ()
        {
            return factory(Department::class)->create()->id;
        },
        'event_id' => function ($shift)
        {
            return Department::find($shift['department_id'])->event->id;
        },
    ];
});
