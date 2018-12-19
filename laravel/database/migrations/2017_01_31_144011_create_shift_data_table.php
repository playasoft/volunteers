<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_data', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->text('roles')->nullable();
            $table->timestamps();
        });

        // Loop through existing shift names and transfer them to the shift data table
        $shifts = DB::table('shifts')->select('shifts.*', 'departments.event_id')->join('departments', 'shifts.department_id', '=', 'departments.id')->get();
        $uniqueNames = [];

        foreach($shifts as $shift)
        {
            // Prevent creating multiple shifts with the same name
            if(!isset($uniqueNames[$shift->department_id]))
            {
                $uniqueNames[$shift->department_id] = [];
            }

            // Use the saved shift_data ID if this shift name is a duplicate
            if(isset($uniqueNames[$shift->department_id][$shift->name]))
            {
                DB::table('shifts')->where('id', $shift->id)->update(['name' => $uniqueNames[$shift->department_id][$shift->name]]);
            }
            else
            {
                $shiftData =
                [
                    'event_id' => $shift->event_id,
                    'department_id' => $shift->department_id,
                    'name' => $shift->name,
                    'created_at' => $shift->created_at,
                    'updated_at' => $shift->updated_at
                ];

                $id = DB::table('shift_data')->insertGetId($shiftData);
                DB::table('shifts')->where('id', $shift->id)->update(['name' => $id]);

                $uniqueNames[$shift->department_id][$shift->name] = $id;
            }
        }

        // Now rename the shift name column
        Schema::table('shifts', function ($table)
        {
            $table->renameColumn('name', 'shift_data_id');
        });

        // And set up the foreign key
        Schema::table('shifts', function ($table)
        {
            $table->integer('shift_data_id')->unsigned()->change();
            $table->foreign('shift_data_id')->references('id')->on('shift_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the shift data foreign key
        Schema::table('shifts', function ($table)
        {
            $table->dropForeign(['shift_data_id']);
        });

        // Now rename the column
        Schema::table('shifts', function ($table)
        {
            $table->renameColumn('shift_data_id', 'name');
        });

        // For some reason this has to be separate as well
        Schema::table('shifts', function ($table)
        {
            $table->string('name')->change();
        });

        // Loop through shift data to get original shift names
        $shifts = DB::table('shifts')->select('shifts.id', 'shift_data.name')->join('shift_data', 'shifts.name', '=', 'shift_data.id')->get();

        foreach($shifts as $shift)
        {
            DB::table('shifts')->where('id', $shift->id)->update(['name' => $shift->name]);
        }

        Schema::drop('shift_data');
    }
}
