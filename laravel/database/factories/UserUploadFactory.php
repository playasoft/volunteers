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
    ];
});
