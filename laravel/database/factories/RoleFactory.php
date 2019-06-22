<?php

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker)
{
    return
    [
        'name' => $faker->unique()->jobTitle,
    ];
});
