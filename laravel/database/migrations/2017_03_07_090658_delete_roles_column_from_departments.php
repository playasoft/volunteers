<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteRolesColumnFromDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all departments
        $departments = DB::table('departments')->get();

        foreach($departments as $department)
        {
            // Copy department roles to shifts belonging to each department
            DB::table('shifts')->where('department_id', $department->id)->update(['roles' => $department->roles]);
        }

        // Remove roles column
        Schema::table('departments', function($table)
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
        // Add roles column back
        Schema::table('departments', function($table)
        {
            $table->text('roles');
        });

        // Get all departments
        $departments = DB::table('departments')->get();

        foreach($departments as $department)
        {
            // Get default roles from the first shift in this department
            $shift = DB::table('shifts')->where('department_id', $department->id)->first();

            if(!empty($shift))
            {
                DB::table('departments')->where('id', $department->id)->update(['roles' => $shift->roles]);
            }
        }
    }
}
