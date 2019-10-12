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
        echo "HGey";
    }

    if(env('APP_DEBUG') && !isset($data['event_id']))
    {
        Log::warning("Using Factory[Shift] without setting event_id");
        echo "Hey";
    }

    return
    [
        'name' => $faker->jobTitle,
        'description' => $faker->bs,
        'event_id' => function ($shift)
        {
            return factory(Event::class)->create()->id;
        },
        'department_id' => function ($shift)
        {
            return factory(Department::class)->create([
                'event_id' => $shift['event_id'],
            ])->id;
        },
    ];
});
