<?php

use Carbon\Carbon;
use App\Models\Department;

$factory->define(Department::class, function (Faker\Generator $faker) {
    return [
        'event_id' => 1,
        'name' => $faker->company,
        'description' => $faker->bs
    ];
});