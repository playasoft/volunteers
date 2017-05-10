<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDuplicateConstraintToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_roles', function (Blueprint $table)
        {
            $table->unique(['role_id', 'event_id'], 'basic_event_role_unique');
            $table->unique(['role_id', 'event_id', 'foreign_id', 'foreign_type'], 'foreign_event_role_unique');
        });

        Schema::table('user_roles', function (Blueprint $table)
        {
            $table->unique(['role_id', 'user_id'], 'basic_user_role_unique');
            $table->unique(['role_id', 'user_id', 'foreign_id', 'foreign_type'], 'foreign_user_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_roles', function (Blueprint $table)
        {
            $table->dropUnique('basic_event_role_unique');
            $table->dropUnique('foreign_event_role_unique');
        });

        Schema::table('user_roles', function (Blueprint $table)
        {
            $table->dropUnique('basic_user_role_unique');
            $table->dropUnique('foreign_user_role_unique');
        });
    }
}
