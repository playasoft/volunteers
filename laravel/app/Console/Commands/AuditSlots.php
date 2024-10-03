<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Slot;

class AuditSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:slots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit all shift slots to look for duplicates';

    private $departments = [];
    private $roles = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dump("Processing all slots...");

        // Select all slots
        $slots = Slot::get();
        $stats = [];
        $duplicates = 0;

        foreach($slots as $slot)
        {
            $uniqueKey = "{$slot->schedule_id} {$slot->start_date} {$slot->start_time} {$slot->end_time} {$slot->row}";

            if(!isset($stats[$uniqueKey]))
            {
                $stats[$uniqueKey] = [];
            }

            $stats[$uniqueKey][] = $slot->id;
        }

        foreach($stats as $uniqueKey => $slots)
        {
            if(count($slots) > 1)
            {
                $duplicates++;
                $this->processDuplicates($slots);
                $this->removeDuplicates($slots);
            }
        }

        dump("================");
        dump("Audit finished.");
        dump("{$duplicates} duplicate slots found.");
        dump(count($this->departments) . " departments impacted.");
        dump(count($this->roles) . " shifts impacted.");

        dump($this->departments);
        dump($this->roles);
    }

    private function processDuplicates($duplicateSlots)
    {
        dump("================");
        echo("Duplicate slots found, processing... \n");
        $duplicateUsers = [];

        foreach($duplicateSlots as $index => $duplicateSlot)
        {
            $slot = Slot::find($duplicateSlot);
            $departmentName = $slot->schedule->department->name;
            $roleName = "{$departmentName} {$slot->schedule->shift->name}";

            if(!isset($this->departments[$departmentName]))
            {
                $this->departments[$departmentName] = 0;
            }

            if(!isset($this->roles[$roleName]))
            {
                $this->roles[$roleName] = 0;
            }

            $this->departments[$departmentName]++;
            $this->roles[$roleName]++;

            if($index === 0)
            {
                // Display slot information during the first iteration of the loop
                echo("Department: {$slot->schedule->department->name} \n");
                echo("Role: {$slot->schedule->shift->name} \n");
                echo("{$slot->start_date} {$slot->start_time} {$slot->end_time} \n");
            }

            if(empty($slot->user))
            {
                echo("{$slot->id} Slot is empty? \n");
            }
            else
            {
                echo("{$slot->id} {$slot->user->name} {$slot->user->email} \n");
                $duplicateUsers[] = $slot->user->id;
            }
        }

        $users = array_unique($duplicateUsers);

        if(count($users) === 0)
        {
            echo "No users detected in either slot. \n";
        }
        else if(count($users) === 1)
        {
            echo "Only 1 impacted user, able to automatically resolve. \n";
        }
        else
        {
            echo "Different users detected. Manual intervention required. \n";
        }
    }

    private function removeDuplicates($duplicateSlots)
    {
        $processedUsers = [];
        $previousUser = null;
        $previousSlot = null;

        foreach($duplicateSlots as $index => $duplicateSlot)
        {
            $slot = Slot::find($duplicateSlot);

            if(empty($slot->user))
            {
                $currentUser = "empty";
            }
            else
            {
                $currentUser = $slot->user->id;
            }

            // If this user has already been processed, then it's safe to delete this slot
            if(in_array($currentUser, $processedUsers))
            {
                dump("!!!!!!!!!!!!!!! DELETE {$slot->id} !!!!!!!!!!!!!!!");
                $slot->delete();
            }

            // If the current user is empty but the previous user existed, then it's safe to delete this slot
            elseif($currentUser === "empty" && $previousUser !== "empty" && !is_null($previousUser))
            {
                dump("!!!!!!!!!!!!!!! DELETE {$slot->id} !!!!!!!!!!!!!!!");
                $slot->delete();
            }

            // If the previous user was empty but the current user exists, then it's safe to delete the previous slot
            elseif($previousUser === "empty" && $currentUser !== "empty")
            {
                dump("!!!!!!!!!!!!!!! DELETE {$previousSlot} !!!!!!!!!!!!!!!");
                $previous = Slot::find($previousSlot);
                $previous->delete();
            }

            $processedUsers[] = $currentUser;
            $previousUser = $currentUser;
            $previousSlot = $slot->id;
        }
    }
}
