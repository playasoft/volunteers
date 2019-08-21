<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\UserData;
use App\Models\UserRole;
use App\Models\Role;


class APIControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function profile_enpoint_returns_right_keys()
    {
        // Given    
        $user = factory(User::class)->states('admin')->create([
            'name' => 'George',
            'email' => 'george@gmail.com',
        ]);
        $user->data()->save(factory(UserData::class)->make([
            'full_name' => 'George Curious',
            'burner_name' => 'CGjungle',
            'phone' => '(123) 456-7890'
        ]));

        // When 
        $response = $this->actingAs($user)->get('/v1/profile');

        // Then 
        $response->assertJson([
            'username' => 'George',
            'email' => 'george@gmail.com',
            'full_name' => 'George Curious',
            'burner_name' => 'CGjungle',
            'phone_number' => '(123) 456-7890',
            'permissions' => ['admin'],
        ]);
    }
}
