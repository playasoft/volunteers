<?php

use Carbon\Carbon;
use \App\Models\Shift;

$factory->define(Shift::class, function (Faker\Generator $faker) {
    static $event_id;
    static $department_id;
    return [
        'event_id' => $event_id ?: $event_id = 1,
        'department_id' => $event_id ?: $event_id = 1,
        'name' => $faker->jobTitle,
        'description' => $faker->bs
    ];
});