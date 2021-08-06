<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableDateTimeToSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            $table->time('start_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();
            $table->time('duration')->nullable()->change();
        });

        Schema::table('slots', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
            $table->time('start_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
            $table->time('start_time')->nullable(false)->change();
            $table->time('end_time')->nullable(false)->change();
            $table->time('duration')->nullable(false)->change();
        });

        Schema::table('slots', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->time('start_time')->nullable(false)->change();
            $table->time('end_time')->nullable(false)->change();
        });
    }
}
