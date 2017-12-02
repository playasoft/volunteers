<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\Slot;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an Event
        $event = factory(Event::class)->create();
        // Create a Department and assign it the Event
        $department = factory(Department::class)->create(['event_id' => $event->id]);
        // Create a Shift and assign it to the Event and Department
        $shift = factory(Shift::class)->create(['event_id' => $event->id, 'department_id' => $department->id]);
        // Create a list of dates between the start and end date of the event
        $dates = [];
        foreach ($event->days() as $day)
        {
            $dates[] = $day->date->toDateString();
        }
        // Create a Schedule and assign it to the Department
        $schedule = factory(Schedule::class)->create(['department_id' => $department->id, 'shift_id' => $shift->id, 'dates' => json_encode($dates)]);
        // Generate slots for the schedule
        Slot::generate($schedule);
    }

}