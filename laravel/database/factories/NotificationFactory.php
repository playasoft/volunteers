<?php

use App\Models\Notification;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker, array $data) {

    if(!isset($data['schedule_id']))
    {
        Log::warning("Using Factory[Notification] without setting user_to");
    }

    return [
        'type' => 'info',
        'status' => 'new',
        'layout' => 'notification-test',
        'metadata' => json_encode([
            'event' => 'test_event',
        ]),
        'user_to' => function() {
            return factory(User::class)->create();
        },
    ];
});
