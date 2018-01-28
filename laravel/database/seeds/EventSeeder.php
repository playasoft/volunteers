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
    private function seedEvent($startDate = null)
    {
        $event = factory(Event::class)->create(['start_date' => $startDate ?: Carbon::now()]);
        echo "Created Event: {$event->name}" . PHP_EOL;

        $department = factory(Department::class)->create(['event_id' => $event->id]);
        echo "Created Department: {$department->name}" . PHP_EOL;

        $shift = factory(Shift::class)->create(['event_id' => $event->id, 'department_id' => $department->id]);
        echo "Created Shift: {$shift->name}" . PHP_EOL;

        // Create a list of dates between the start and end date of the event
        $dates = [];
        foreach ($event->days() as $day)
        {
            $dates[] = $day->date->toDateString();
        }

        $schedule = factory(Schedule::class)->create(['department_id' => $department->id, 'shift_id' => $shift->id, 'dates' => json_encode($dates)]);
        echo "Created Schedule: {$schedule->id}". PHP_EOL;
        echo "\tin department: {$schedule->department->name}". PHP_EOL;
        echo "\tfor shift: {$schedule->shift->name}" . PHP_EOL;

        echo "Generating slots for schedule: {$schedule->id}" . PHP_EOL;
        Slot::generate($schedule);

        // Generate some test users for the slots
        echo "Creating users and assigning to slots" . PHP_EOL;
        $users = factory(User::class, 10)->create()->each(function ($u)
        {
            UserRole::assign($u, ['fire', 'volunteer']);
            factory(UserData::class)->create(['user_id' => $u->id]);
        });

        // Assign users to slots
        $slots = Slot::where('schedule_id', $schedule->id)->get();

        foreach($slots as $slot)
        {
            // Skip 1/3 slots to avoid filling all slots
            if (rand(0, 2) == 0)
            {
                continue;
            }

            $slot->user_id = $users->random()->id;
            $slot->save();
        }
        echo "Done!";
    }
    
    /**
     * Run the database seeds.
     * Usage: php artisan db:seed --class=EventSeeder
     * @return void
     */
    public function run()
    {
        $this->seedEvent(Carbon::now());
        $this->seedEvent(Carbon::now()->subWeeks(2));
        $this->seedEvent(Carbon::now()->addWeeks(2));
    }

}