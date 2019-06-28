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
         * If you wish to setup a model without key depedencies met,
         * use "factory()". This way you're more cognisant of how you
         * build seeders for non-testing purposes.
         */
        factory(User::class)->create();

        /**
         * If you wish to setup a model with dependencies met,
         * use the "with_setup" state. factoryWithSetup() is an
         * alias for "factory(<CLASS_NAME>)->states('with_setup')".
         */
        factoryWithSetup(User::class)->create();

        /**
         * If you want to break up repeating seeder functionality
         * to different, smaller seeders; then, you can call them
         * with this functions at any time.
         */
        $this->call(DatabaseSeeder::class);
    }
}
