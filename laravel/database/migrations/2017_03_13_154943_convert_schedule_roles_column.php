<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertScheduleRolesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all data from the schedule
        $schedules = DB::table('schedule')->get();

        foreach($schedules as $schedule)
        {
            if(empty($schedule->roles))
            {
                continue;
            }

            // Loop through each role used for this schedule
            $scheduleRoles = json_decode($schedule->roles);

            foreach($scheduleRoles as $scheduleRole)
            {
                $role = DB::table('roles')->where('name', $scheduleRole)->first();
                $shift = DB::table('shifts')->where('id', $schedule->shift_id)->first();

                // Create a new row in the event_roles table
                if(!empty($role))
                {
                    $roleData =
                    [
                        'role_id' => $role->id,
                        'event_id' => $shift->event_id,
                        'foreign_id' => $schedule->id,
                        'foreign_type' => 'App\Models\Schedule'
                    ];

                    DB::table('event_roles')->insert($roleData);
                }
            }
        }

        // Remove roles column from schedule table
        Schema::table('schedule', function($table)
        {
            $table->dropColumn('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add the column back
        Schema::table('schedule', function ($table)
        {
            $table->text('roles')->nullable();
        });

        // Loop through all schedule data
        $schedules = DB::table('schedule')->get();

        foreach($schedules as $schedule)
        {
            // Get the current event_roles
            $scheduleRoles = DB::table('event_roles')->
                                join('roles', 'event_roles.role_id', '=', 'roles.id')->
                                where('foreign_id', $schedule->id)->
                                where('foreign_type', 'App\Models\Schedule')->
                                get();

            // Loop through schedule roles and make an array of their names
            $scheduleRoleNames = [];

            foreach($scheduleRoles as $scheduleRole)
            {
                $scheduleRoleNames[] = $scheduleRole->name;
            }

            if(!empty($scheduleRoleNames))
            {
                // Add it back to the schedule table
                DB::table('schedule')->where('id', $schedule->id)->update(['roles' => json_encode($scheduleRoleNames)]);
            }
        }

        // Clear out the event_roles table
        DB::table('event_roles')->where('foreign_type', 'App\Models\Schedule')->delete();
    }
}
