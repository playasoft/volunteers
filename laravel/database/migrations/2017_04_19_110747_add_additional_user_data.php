<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalUserData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->timestamp('login_at')->nullable()->after('updated_at');
        });

        Schema::table('user_data', function(Blueprint $table)
        {
            $table->renameColumn('real_name', 'full_name');
            $table->string('phone')->nullable()->after('birthday');
            $table->string('emergency_name')->nullable()->after('phone');
            $table->string('emergency_phone')->nullable()->after('emergency_name');
            $table->string('camp')->nullable()->after('emergency_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn('login_at');
        });

        Schema::table('user_data', function(Blueprint $table)
        {
            $table->renameColumn('full_name', 'real_name');
            $table->dropColumn('phone');
            $table->dropColumn('emergency_name');
            $table->dropColumn('emergency_phone');
            $table->dropColumn('camp');
        });
    }
}
