<?php

use App\Models\User;
use App\Models\UserData;
use Faker\Generator as Faker;

$factory->define(UserData::class, function (Faker $faker)
{
    return
        [
        'burner_name' => $faker->firstName,
        'full_name' => $faker->name,
        'birthday' => $faker->dateTimeThisCentury->format('Y-m-d'),
        'phone' => $faker->phoneNumber,
        'emergency_name' => $faker->name,
        'emergency_phone' => $faker->phoneNumber,
        'camp' => "open camping",
    ];
});

$factory->state(UserData::class, 'with_setup', function (Faker $faker)
{
    return
        [
        'user_id' => function ()
        {
            return factory(User::class)->states('with_setup')->create()->id;
        },
    ];
});
