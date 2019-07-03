<?php

use App\Models\User;
use App\Models\UserUpload;
use Faker\Generator as Faker;

$factory->define(UserUpload::class, function (Faker $faker, array $data)
{
    if(env('APP_DEBUG') && !isset($data['user_id']))
    {
        Log::warning("Using Factory[UserUpload] without setting user_id");
    }

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
