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
        // TODO: 
        // Upgrade to Laravel 5.2+ and use Schema::disableForeignKeyConstraints()
        // Instead of dropping the foreign keys.
        Schema::table('event_roles', function (Blueprint $table)
        {
            $table->dropForeign('event_roles_role_id_foreign');
            $table->dropForeign('event_roles_event_id_foreign');

            $table->dropUnique('event_role_unique');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');            
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->integer('foreign_id')->unsigned()->nullable()->change();
            $table->string('foreign_type')->nullable()->change();
        });

        Schema::table('user_roles', function (Blueprint $table)
        {
            $table->dropForeign('user_roles_role_id_foreign');
            $table->dropForeign('user_roles_user_id_foreign');

            $table->dropUnique('user_role_unique');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('foreign_id')->unsigned()->nullable()->change();
            $table->string('foreign_type')->nullable()->change();
        });
    }
}
