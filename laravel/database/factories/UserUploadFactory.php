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
    ];
});

$factory->state(UserUpload::class, 'with-setup', function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->states('with-setup')->create()->id,
        'admin_id' => factory(User::class)->states('admin','with-setup')->create()->id,
    ];
});
