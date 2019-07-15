<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker, array $data)
{
    return
    [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt($faker->password),
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

$factory->afterCreatingState(User::class, 'department-lead', function (User $user, Faker $faker)
{
    //find the admin role
    $role_name = 'department-lead';
    $admin_role = Role::where('name', $role_name)->first();
    //if there is no admin role, create it
    if (!$admin_role)
    {
        $admin_role = factory(Role::class)->create([
            'name' => $role_name,
        ]);
    }

    $user->roles()->save(factory(UserRole::class)->make([
        'role_id' => $admin_role->id,
        'user_id' => $user->id,
    ]));
});
