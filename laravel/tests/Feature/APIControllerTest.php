<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Helpers;
use App\Models\User;
use App\Models\UserData;
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
    public function profile_endpoint_validation()
    {
        // Given 
        $user = factory(UserData::class)->create([
            'user_id' => factory(User::class)->states('department-lead')->create()->id,
        ])->user;

        // When 
        $response = $this->actingAs($user)->get('/v1/profile');

        // Then 
        $response->assertJson([
            'username' => $user->name,
            'email' => $user->email,
            'full_name' => $user->data->full_name,
            'burner_name' => $user->data->burner_name,
            'phone_number' => $user->data->phone,
            'permissions' => $user->getRoleNames(),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function profile_endpoint_validation_admin()
    {
        // Given 
        $user = factory(UserData::class)->create([
            'user_id' => factory(User::class)->states('admin')->create()->id,
        ])->user;

        // When 
        $response = $this->actingAs($user)->get('/v1/profile');

        // Then 
        $response->assertJson([
            'username' => $user->name,
            'email' => $user->email,
            'full_name' => $user->data->full_name,
            'burner_name' => $user->data->burner_name,
            'phone_number' => $user->data->phone,
            'permissions' => $user->getRoleNames(),
        ]);
    }


    /**
     * @test
     * 
     * @return void
     */
    public function events_endpoint_validation()
    {
        // Given 
        $user = factory(User::class)->states('department-lead')->create();
        $event = factory(Event::class)->create();

        // When 
        $response = $this->actingAs($user)->get('/v1/events');

        // Then 
        $response->assertJson([
            [
                'event_id' => $event->id,
                'name' => $event->name,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
            ],
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function departments_endpoint_validation()
    {
        // Given 
        $user = factory(User::class)->states('department-lead')->create();
        $department = factory(Department::class)->create();
        $event = $department->event;

        // Then 
        $response = $this->actingAs($user)->get("/v1/event/{$event->id}/departments");

        // When 
        $response->assertJson([
            [
                'department_id' => $department->id,
                'name' => $department->name,
            ],
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function roles_endpoint_validation()
    {
        // Given 
        $user = factory(User::class)->states('department-lead')->create();
        $role = factory(Shift::class)->create();
        $event = $role->event;

        // Then 
        $response = $this->actingAs($user)->get("/v1/event/{$event->id}/roles");

        // When 
        $response->assertJson([
            [
                'role_id' => $role->id,
                'department_id' => $role->department->id,
                'name' => $role->name,
            ],
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function shifts_endpoint_validation()
    {
        // Given 
        $user = factory(UserData::class)->create([
            'user_id' => factory(User::class)->states('department-lead')->create()->id,
        ])->user;
        $shift = factory(Slot::class)->create([
            'user_id' => $user->id,
        ]);
        $event = $shift->schedule->department->event;


        // Then 
        $response = $this->actingAs($user)->get("/v1/event/{$event->id}/shifts");

        // When 
        $response->assertJson([
            [
                'shift_id' => $shift->id,
                'department_id' => $shift->department->id,
                'role_id' => $shift->schedule->shift->id,
                'start_date' => $shift->schedule->start_date,
                'end_date' => $shift->schedule->end_date,
                'start_time' => $shift->schedule->start_time,
                'end_time' => $shift->schedule->end_time,
                'user_id' => $shift->user->id,
                'email' => $shift->user->email,
                'full_name' => $shift->user->data->full_name,
                'status' => $shift->status,
                'display_name' => Helpers::displayName($user),
            ],
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function update_shift_endpoint_validation()
    {
        // Given 
        $user = factory(User::class)->states('department-lead')->create();
        $shift = factory(Slot::class)->create([
            'user_id' => $user->id,
        ]);

        // When 
        $response = $this->actingAs($user)->post("/v1/shift/{$shift->id}", [
            'status' => 'test',
        ]);

        // Then 
        $response->assertStatus(200);
        $this->assertDatabaseHas('slots', [
            'id' => $shift->id,
            'status' => 'test',
        ]);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function profile_returns_401_when_no_user()
    {
        // Given 
        $user = factory(User::class)->create();

        // When 
        $response = $this->actingAs($user)->get('/v1/profile');

        // Then 
        $response->assertStatus(401);
    }
}
