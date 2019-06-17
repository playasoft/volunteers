<?php

use App\Models\Department;
use App\Models\Schedule;
use App\Models\Shift;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Schedule::class, function (Faker $faker, $data)
{
    $duration_min = 2; //hours
    $duration_max = 8; //hours

    $volunteer_min = 1;
    $volunteer_max = 3;

    $start_datetime = Carbon::tomorrow();
    $end_datetime = $start_datetime->copy()->addWeeks($faker->randomDigitNotNull);

    $duration = Carbon::createFromTime($faker->numberBetween($duration_min, $duration_max));
    $start_time = Carbon::createFromTime($faker->numberBetween(0, 23));
    $end_time = $start_time->addHours($duration->hour);

    return [
        'start_date' => $start_datetime->format('Y-m-d'),
        'end_date' => $end_datetime->format('Y-m-d'),
        'start_time' => $start_time->format('H:M:S'),
        'end_time' => $end_time->format('H:M:S'),
        'duration' => $duration->format('H:M:S'),
        'volunteers' => $faker->numberBetween($volunteer_min, $volunteer_max),
    ];
});

$factory->state(Schedule::class, 'with_setup', function (Faker $faker, $data)
{
    return [
        'department_id' => function ()
        {
            return factory(Department::class)->states('with_setup')->create()->id;
        },
        'shift_id' => function ($schedule)
        {
            $department = Department::find($schedule['department_id']);
            return factory(Shift::class)->states('with_setup')->create([
                'department_id' => $department->id,
                'event_id' => $department->event->id,
            ])->id;
        },
    ];
});
