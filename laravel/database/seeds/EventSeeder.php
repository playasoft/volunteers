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
use App\Models\UserRole;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private function seedEvent($startDate)
    {
        // Create an Event
        $event = factory(Event::class)->create(['start_date' => $startDate]);
        dump("Created Event: ". $event->name);
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
        dump("Generating slots for schedule: " . $schedule->id);
        Slot::generate($schedule);

        // Generate some test users for the slots
        dump("Creating users and assigning to slots");
        $users = factory(User::class, 10)->create()
            ->each( function ($u) {
                UserRole::assign($u, ['fire', 'volunteer']);
                factory(UserData::class)->create(['user_id' => $u->id]);
            });

        // Assign users to slots
        $slots = Slot::where('schedule_id', $schedule->id)->get();
        $i = 0;
        foreach($slots as $slot)
        {
            // Only assign to every second slot
            if ($i++ % 2 == 0)
                continue;

            $slot->user_id = $users->random()->id;
            $slot->save();
        }
        dump("Done!");
    }
    
    public function run()
    {
        $this->seedEvent(Carbon::now());
        $this->seedEvent(Carbon::now()->subWeeks(2));
        $this->seedEvent(Carbon::now()->addWeeks(2));
    }

}