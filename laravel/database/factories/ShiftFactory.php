<?php

use App\Helpers;
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
        'department_id' => function ($shift) use ($data)
        {
            $event_subset = Helpers::subsetArray($data, [ 
                'event_id',
            ]);
            return factory(Department::class)->create($event_subset)->id;
        },
        'event_id' => function ($shift)
        {
            return factory(Event::class)->create()->id;
        },
    ];
});
