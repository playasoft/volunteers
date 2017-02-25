<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameShiftsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Start out by dropping foreign keys
        Schema::table('shifts', function ($table)
        {
            $table->dropForeign(['shift_data_id']);
        });

        Schema::table('slots', function ($table)
        {
            $table->dropForeign(['shift_id']);
        });

        // Now rename the tables
        Schema::rename('shifts', 'schedule');
        Schema::rename('shift_data', 'shifts');

        // Rename the columns and create new foreign keys
        Schema::table('schedule', function ($table)
        {
            $table->renameColumn('shift_data_id', 'shift_id');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
        });

        Schema::table('slots', function ($table)
        {
            $table->renameColumn('shift_id', 'schedule_id');
            $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Start out by dropping foreign keys
        Schema::table('schedule', function ($table)
        {
            $table->dropForeign(['shift_id']);
        });

        Schema::table('slots', function ($table)
        {
            $table->dropForeign(['schedule_id']);
        });

        // Now rename the tables
        Schema::rename('shifts', 'shift_data');
        Schema::rename('schedule', 'shifts');

        // Rename the columns and create new foreign keys
        Schema::table('shifts', function ($table)
        {
            $table->renameColumn('shift_id', 'shift_data_id');
            $table->foreign('shift_data_id')->references('id')->on('shift_data')->onDelete('cascade');
        });

        Schema::table('slots', function ($table)
        {
            $table->renameColumn('schedule_id', 'shift_id');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
        });
    }
}
