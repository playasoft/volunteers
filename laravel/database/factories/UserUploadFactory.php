<?php

use App\Models\User;
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

$factory->state(UserUpload::class, 'test', function (Faker $faker)
{
    return [
        'user_id' => function ()
        {
            return factory(User::class)->states('test')->create()->id;
        },
        'admin_id' => function ()
        {
            return factory(User::class)->states('admin', 'test')->create()->id;
        },
    ];
});
