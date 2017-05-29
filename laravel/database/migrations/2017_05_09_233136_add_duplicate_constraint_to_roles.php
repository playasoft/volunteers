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
            $table->integer('foreign_id')->unsigned()->nullable(false)->change();
            $table->string('foreign_type')->nullable(false)->change();

            $table->unique(['role_id', 'event_id', 'foreign_id', 'foreign_type'], 'event_role_unique');
        });

        Schema::table('user_roles', function (Blueprint $table)
        {
            $table->integer('foreign_id')->unsigned()->nullable(false)->change();
            $table->string('foreign_type')->nullable(false)->change();

            $table->unique(['role_id', 'user_id', 'foreign_id', 'foreign_type'], 'user_role_unique');
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
            $table->dropUnique('event_role_unique');

            $table->integer('foreign_id')->unsigned()->nullable()->change();
            $table->string('foreign_type')->nullable()->change();
        });

        Schema::table('user_roles', function (Blueprint $table)
        {
            $table->dropUnique('user_role_unique');

            $table->integer('foreign_id')->unsigned()->nullable()->change();
            $table->string('foreign_type')->nullable()->change();
        });
    }
}
