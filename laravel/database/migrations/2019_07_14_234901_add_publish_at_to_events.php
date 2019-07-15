<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishAtToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable();
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable();
        });
        Schema::table('shifts', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable();
        });
        Schema::table('schedule', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable();
        });
        Schema::table('slots', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
        Schema::table('schedule', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
        Schema::table('slots', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
}
