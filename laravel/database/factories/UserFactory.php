<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
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

$factory->state(User::class, 'admin', function (Faker $faker)
{
    return
    [
    ];
})->afterCreating(User::class, function ($user, $faker) {
    $admin_role = Role::where('name', 'admin')->first();
    if(!$admin_role) {
        $admin_role = factory(Role::class)->create([
            'name' => 'admin'
        ]);
    }
    $user->roles()->save(factory(UserRole::class)->make([
        'role_id' => $admin_role->id,
        'user_id' => $user->id
    ]));
});;

// $factory->afterCreatingState(User::class, 'admin', function ($user, Faker\Generator $faker)
// {
//     $user->roles()->save(factory(UserRole::class)->make([
//         'name' => 'admin'
//     ]));
// });
