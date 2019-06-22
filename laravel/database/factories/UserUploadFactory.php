<?php

use App\Models\User;
use App\Models\UserUpload;
use Faker\Generator as Faker;

$factory->define(UserUpload::class, function (Faker $faker)
{
    return
    [
        'name' => 'test_file.' . $faker->fileExtension,
        'description' => $faker->paragraph(),
        'file' => '',
        'notes' => $faker->sentence(),
        'user_id' => function ()
        {
            return factory(User::class)->create()->id;
        },
    ];
});
