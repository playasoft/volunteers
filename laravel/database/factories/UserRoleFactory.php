<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Faker\Generator as Faker;

$factory->define(UserRole::class, function (Faker $faker)
{
    return
    [
        'foreign_id' => 0,
        'foreign_type' => '',
        'role_id' => function ()
        {
            return factory(Role::class)->create()->id;
        },
        'user_id' => function ()
        {
            return factory(User::class)->create()->id;
        },
    ];
});
