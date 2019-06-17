<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Faker\Generator as Faker;

$factory->define(UserRole::class, function (Faker $faker)
{
    return [
        'foreign_id' => 0,
        'foreign_type' => '',
    ];
});

$factory->state(UserRole::class, 'test', function (Faker $faker)
{
    return [
        'role_id' => function ()
        {
            return factory(Role::class)->states('test')->create()->id;
        },
        'user_id' => function ()
        {
            return factory(User::class)->states('test')->create()->id;
        },
    ];
});
