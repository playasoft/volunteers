<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker)
{
    return
        [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt($faker->password),
    ];
});

$factory->state(User::class, 'with_setup', function (Faker $faker)
{
    return
        [
    ];
});
