<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserData;
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

$factory->afterCreating(User::class, function (User $user, Faker $faker)
{

    

});
