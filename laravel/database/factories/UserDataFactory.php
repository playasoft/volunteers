<?php

use Carbon\Carbon;
use App\Models\UserData;

$factory->define(UserData::class, function (Faker\Generator $faker) {
    return [
        'burner_name' => $faker->firstName,
        'full_name' => $faker->name,
        'birthday' => $faker->dateTimeThisCentury->format('Y-m-d'),
        'phone' => $faker->phoneNumber,
        'emergency_name' => $faker->name,
        'emergency_phone' => $faker->phoneNumber,
        'camp' => "open camping",
    ];
});
    