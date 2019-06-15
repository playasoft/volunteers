<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Event;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Slot;
use App\Models\User;
use App\Models\UserData;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlotAssignTest extends TestCase
{
    use RefreshDatabase;

    /**
     * [setUpBeforeClass description]
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        factory(UserData::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->event = factory(Event::class)->create();
        $this->department = factory(Department::class)->create([
            'event_id' => $this->event->id
        ]);
        $this->shift = factory(Shift::class)->create();
        $this->schedule = factory(Schedule::class)->create();
        $this->slot = factory(Slot::class)->create();
    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function user_can_see_a_slot()
    {
        $response = $this->actingAs($this->user)
            ->get('/slot/' . $this->slot->id . '/view');

        $response->assertStatus(200);
    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function user_can_take_a_slot()
    {
        $response = $this->actingAs($this->user)
            ->post('/slot/' . $this->slot->id . '/take');

        var_dump($response->getContent());

        $response->assertRedirect('/');

        $response->assertJson(['warning' => $this->slot->id]);
    }
}
