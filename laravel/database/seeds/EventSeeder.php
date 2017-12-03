<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\Slot;
use App\Models\User;
use App\Models\UserData;

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
        dump("Created Event: " . $event->name);

        // Create a Department and assign it the Event
        $department = factory(Department::class)->create(['event_id' => $event->id]);
        dump("Created Department: " . $department->name);

        // Create a Shift and assign it to the Event and Department
        $shift = factory(Shift::class)->create(['event_id' => $event->id, 'department_id' => $department->id]);
        dump("Created Shift: " . $shift->name);

        // Create a list of dates between the start and end date of the event
        $dates = [];
        foreach ($event->days() as $day)
        {
            $dates[] = $day->date->toDateString();
        }

        // Create a Schedule and assign it to the Department
        $schedule = factory(Schedule::class)->create(['department_id' => $department->id, 'shift_id' => $shift->id, 'dates' => json_encode($dates)]);
        dump("Created Schedule: " . $schedule->id . " in department: " . $schedule->department->name . " for shift: " . $schedule->shift->name);

        // Generate slots for the schedule
        Slot::generate($schedule);
        dump("Generating slots for schedule: " . $schedule->id);
        
        // Create and assign Users to random slots.
        dump("Creating users and assigning to slots");
        $slots = Slot::where('schedule_id', $schedule->id)->get();
        foreach($slots as $slot)
        {
            $user = factory(User::class)->create();
            factory(UserData::class)->create(['user_id' => $user->id]);
            $slot->user_id = $user->id;
            $slot->save(); 
        }
        dump("Done!");
    }

}