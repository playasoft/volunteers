<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    //Use this to clear the database at the beginning of the test class
    use RefreshDatabase;

    /**
     * Make sure to use either "test" in the method name
     * or use "@test" in the block comment above the test.
     *
     * Use...
     *
     *      factory($model_class);
     *
     * ...when a test calls for a particular model to use.
     * This minimizes the test code you need to write by
     * giving you models with randomly supplied dependencies.
     * With "overriding" you can then specify relations between
     * models and illustrate more complex tests if needed.
     *
     * PHPUnit is used as the prime testing framework for
     * assertions in Feature and Unit test. Browser tests use
     * PHPUnit and a Laravel Dusk.
     *
     * NOTE: If tests require a complex setup, consider "seeders".
     * See the ExampleSeeder for more info.
     *
     * @test
     *
     * @return void
     */
    public function testExample()
    {
        //Given
        $user_id = 1; //first autoincrement id

        //When
        $user = factory(User::class)->create([
            'id' => $user_id,
        ]);

        //Then
        $this->assertDatabaseHas('users', [
            'id' => $user_id,
        ]);
    }
}
