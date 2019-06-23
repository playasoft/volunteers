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

$factory->state(User::class, 'admin', function (Faker $faker)
{
    return
    [
    ];
});

$factory->afterCreatingState(User::class, 'admin', function (User $user, Faker $faker)
{
    //find the admin role
    $admin_role = Role::where('name', 'admin')->first();
    //if there is no admin role, create it
    if (!$admin_role)
    {
        $admin_role = factory(Role::class)->create([
            'name' => 'admin',
        ]);
    }

    $user->roles()->save(factory(UserRole::class)->make([
        'role_id' => $admin_role->id,
        'user_id' => $user->id,
    ]));
});
