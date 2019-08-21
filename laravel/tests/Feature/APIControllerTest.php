<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\UserData;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\Event;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Slot;


class APIControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function profile_endpoint_returns_right_keys()
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

    /**
     * @test
     * 
     * @return void
     */
    public function events_endpoint_returns_right_keys()
    {
        // Given 
        $user = factory(User::class)->states('admin')->create();
        $event1 = factory(Event::class)->create([
            'name' => 'CoolCon'
        ]);
        $event2 = factory(Event::class)->create([
            'name' => 'LameCon',
        ]);

        // When 
        $response = $this->actingAs($user)->get('/v1/events');

        // Then 
        $response->assertJson([
            [
                'id' => $event1->id,
                'name' => $event1->name,
                'start_date' => $event1->start_date,
                'end_date' => $event1->end_date,
            ],
            [
                'id' => $event2->id,
                'name' => $event2->name,
                'start_date' => $event2->start_date,
                'end_date' => $event2->end_date,
            ],
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function departments_endpoint_returns_right_keys()
    {
        // Given 
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create();
        $departments = factory(Department::class,2)->create([
            'event_id' => $event->id,
        ]);

        // Then 
        $response = $this->actingAs($user)->get("/v1/event/{$event->id}/departments");

        // When 
        $response->assertJson([
            [
                'id' => $departments[0]->id,
                'name' => $departments[0]->name,
            ],
            [
                'id' => $departments[1]->id,
                'name' => $departments[1]->name,
            ],
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function roles_endpoint_returns_right_keys()
    {
        // Given 
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create();
        $department = factory(Department::class)->create([
            'event_id' => $event->id,
        ]);
        $roles = factory(Shift::class,2)->create([
            'event_id' => $event->id,
            'department_id' => $department->id,
        ]);

        // Then 
        $response = $this->actingAs($user)->get("/v1/event/{$event->id}/roles");

        // When 
        $response->assertJson([
            [
                'id' => $roles[0]->id,
                'department_id' => $department->id,
                'name' => $roles[0]->name,
            ],
            [
                'id' => $roles[1]->id,
                'department_id' => $department->id,
                'name' => $roles[1]->name,
            ],
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function shifts_endpoint_returns_right_keys()
    {
        // Given 
        $user = factory(UserData::class)->create()->user;
        $shift = factory(Slot::class)->create([
            'user_id' => $user->id,
        ]);


        // Then 
        $response = $this->actingAs($user)->get("/v1/event/{$event->id}/shifts");

        // When 
        // $response->assertJson([
        //     [
        //         'id' => $shift->id,
        //         'department_id' => $shift->department->id,
        //     ],
        //     [
        //         'id' => $roles[1]->id,
        //         'department_id' => $department->id,
        //         'name' => $roles[1]->name,
        //     ],
        // ]);
        dd($response);
    }
}
