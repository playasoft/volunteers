<?php

use Carbon\Carbon;
use App\Models\User;

$factory->define(User::class, function (Faker\Generator $faker)
{
    return
    [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt($faker->password),
    ];
});
