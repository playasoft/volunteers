<?php

use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    /**
     * Seed the database with test/basic information.
     *
     * NOTE: Keep in mind this has no return value,
     * meaning context, or objects, cannot be passed
     * back to the caller. So queries to retrieve
     * particular data or running database assertions
     * are the only way to observe this data.
     *
     * @return void
     */
    public function run()
    {
        /**
         * If you wish to setup a model with dependencies met,
         * use the "factory()". Otherwise you'll have to
         * fill out dependencies manually.
         */
        factory(User::class)->create();

        /**
         * If you want to break up repeating seeder functionality
         * to different, smaller seeders; then, you can call them
         * with this functions at any time.
         */
        $this->call(DatabaseSeeder::class);
    }
}
