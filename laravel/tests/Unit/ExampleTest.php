<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * Make sure to use either "test" in the method name
     * or use "@test" in the block comment above the test.
     *
     * Use...
     *
     *      $this->factoryWithSetup($model_class);
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
     * @test
     *
     * @return void
     */
    public function testExample()
    {
        //Given
        $user_name = 'Frank the Tester';

        //When
        $user = $this->factoryWithSetup(User::class)->create([
            'name' => $user_name,
        ]);

        //Then
        $this->assertDatabaseHas('users', [
            'name' => $user_name,
        ]);
    }
}
