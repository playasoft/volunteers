<?php

use Carbon\Carbon;
use App\Models\Shift;

$factory->define(Shift::class, function (Faker\Generator $faker)
{
    return
    [
        'event_id' => 1,
        'department_id' => 1,
        'name' => $faker->jobTitle,
        'description' => $faker->bs
    ];
});
