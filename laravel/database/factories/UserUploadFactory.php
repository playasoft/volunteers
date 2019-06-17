<?php

use Faker\Generator as Faker;
use App\Models\UserUpload;
use App\Models\User;

$factory->define(UserUpload::class, function (Faker $faker) {
    return [
        'name' => 'test_file.'.$faker->fileExtension,
        'description' => $faker->paragraph(),
        'file' => '',
        'notes' => $faker->sentence(),
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'admin_id' => function() {
            return factory(User::class)->states('admin')->create()->id;
        },
    ];
});
