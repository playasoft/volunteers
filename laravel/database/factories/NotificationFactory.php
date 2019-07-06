<?php

use App\Models\Notification;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        'type' => 'info',
        'status' => 'new',
        'metadata' => 'so meta',
        'user_to' => function() {
            return factory(User::class)->create();
        },
    ];
});
