<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserUpload;
use Faker\Generator as Faker;

$factory->define(UserUpload::class, function (Faker $faker)
{
    return [
        'name' => 'test_file.' . $faker->fileExtension,
        'description' => $faker->paragraph(),
        'file' => '',
        'notes' => $faker->sentence(),
    ];
});

$factory->state(UserUpload::class, 'with_setup', function (Faker $faker)
{
    return [
        'user_id' => function ()
        {
            return factory(User::class)->states('with_setup')->create()->id;
        },
        'admin_id' => function ()
        {
            //generate the admin role
            $admin_role = Role::where('name', 'admin')->first();
            if (!$admin_role)
            {
                $admin_role = factory(Role::class)->create([
                    'name' => 'admin',
                ]);
            }

            $user = factory(User::class)->states('with_setup')->create();
            $admin = $user->roles()->save(factory(UserRole::class)->make([
                'role_id' => $admin_role->id,
                'user_id' => $user->id,
            ]));
        },
    ];
});
