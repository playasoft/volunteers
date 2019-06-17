<?php

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker)
{
    return [
        'name' => $faker->unique()->jobTitle,
    ];
});

$factory->state(Role::class, 'with_setup', function (Faker $faker)
{
    return [
    ];
});
