<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertUserRoleColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all users
        $users = DB::table('users')->get();

        foreach($users as $user)
        {
            // Search the roles table for the ID of the current user role
            $userRole = DB::table('roles')->where('name', $user->role)->first();

            // Create a new row in the user_roles table
            if(!empty($userRole))
            {
                DB::table('user_roles')->insert(['role_id' => $userRole->id, 'user_id' => $user->id]);

                // Make sure everyone has the volunteer role by default
                if($userRole->name != "volunteer")
                {
                    $volunteerRole = DB::table('roles')->where('name', 'volunteer')->first();

                    DB::table('user_roles')->insert(['role_id' => $volunteerRole->id, 'user_id' => $user->id]);
                }
            }
        }

        // Remove role column from users table
        Schema::table('users', function($table)
        {
            $table->dropColumn('role');
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
        Schema::table('users', function ($table)
        {
            $table->enum('role', ['admin', 'volunteer', 'veteran', 'medical', 'fire']);
        });

        // Loop through all users
        $users = DB::table('users')->get();

        foreach($users as $user)
        {
            // Get their current user_role
            $userRole = DB::table('user_roles')->join('roles', 'user_roles.role_id', '=', 'roles.id')->where('user_id', $user->id)->first();

            if(!empty($userRole))
            {
                // Add it back to the users table
                DB::table('users')->where('id', $user->id)->update(['role' => $userRole->name]);
            }
        }

        // Clear out the user_roles table
        DB::table('user_roles')->truncate();
    }
}
