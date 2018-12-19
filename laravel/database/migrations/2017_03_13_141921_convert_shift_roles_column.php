<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertShiftRolesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all shifts
        $shifts = DB::table('shifts')->get();

        foreach($shifts as $shift)
        {
            // Loop through each role used for this shift
            $shiftRoles = json_decode($shift->roles);

            foreach($shiftRoles as $shiftRole)
            {
                $role = DB::table('roles')->where('name', $shiftRole)->first();

                // Create a new row in the event_roles table
                if(!empty($role))
                {
                    $roleData =
                    [
                        'role_id' => $role->id,
                        'event_id' => $shift->event_id,
                        'foreign_id' => $shift->id,
                        'foreign_type' => 'App\Models\Shift'
                    ];

                    DB::table('event_roles')->insert($roleData);
                }
            }
        }

        // Remove roles column from shifts table
        Schema::table('shifts', function($table)
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
        Schema::table('shifts', function ($table)
        {
            $table->text('roles')->nullable();
        });

        // Loop through all shifts
        $shifts = DB::table('shifts')->get();

        foreach($shifts as $shift)
        {
            // Get their current event_roles
            $shiftRoles = DB::table('event_roles')->
                                join('roles', 'event_roles.role_id', '=', 'roles.id')->
                                where('foreign_id', $shift->id)->
                                where('foreign_type', 'App\Models\Shift')->
                                get();

            // Loop through shift roles and make an array of their names
            $shiftRoleNames = [];

            foreach($shiftRoles as $shiftRole)
            {
                $shiftRoleNames[] = $shiftRole->name;
            }

            // Add it back to the shifts table
            DB::table('shifts')->where('id', $shift->id)->update(['roles' => json_encode($shiftRoleNames)]);
        }

        // Clear out the event_roles table
        DB::table('event_roles')->where('foreign_type', 'App\Models\Shift')->delete();
    }
}
