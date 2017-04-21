<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUploadStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `user_uploads` MODIFY COLUMN `status` ENUM('pending','approved-medical','approved-fire','approved-ranger','denied')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `user_uploads` MODIFY COLUMN `status` ENUM('pending','approved','denied')");
    }
}
