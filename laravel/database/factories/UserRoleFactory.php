<?php

use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

$factory->define(UserRole::class, function (Faker $faker) {
    return [
        'foreign_id' => 0,
        'foreign_type' => ''
    ];
});

$factory->state(UserRole::class, 'with-setup',function (Faker $faker) {
    return [
        'role_id' => factory(Role::class)->create()->id,
        'user_id' => factory(User::class)->create()->id
    ];
});
