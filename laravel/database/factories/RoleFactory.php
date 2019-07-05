<?php

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker, array $data)
{
    return
    [
        'name' => $faker->unique()->jobTitle,
    ];
});
