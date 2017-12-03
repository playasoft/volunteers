<?php

use Carbon\Carbon;
use \App\Models\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->userName,
        'email' => $faker->safeEmail,
        'password' => bcrypt($faker->password),
    ];
});
    