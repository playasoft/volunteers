<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\UserData;
use App\Models\Slot;

class CheckPublishedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 
     * @return void
     */
    public function unpublished_event_viewable_by_admin()
    {
        // Given 
        $admin = factory(User::class)->states('admin')->create();

        $admin->data()->save(factory(UserData::class)->make());
        $slot = factory(Slot::class)->create();

        // When 
        $response = $this->actingAs($admin)->get("/slot/{$slot->id}/view");

        // Then 
        $response->assertStatus(200);
    }

        /**
     * @test
     * 
     * @return void
     */
    public function unpublished_event_not_viewable_by_volunteer()
    {
        // Given 
        $volunteer = factory(User::class)->create();
        $slot = factory(Slot::class)->create();

        // When 
        $response = $this->actingAs($volunteer)->get("/slot/{$slot->id}/view");

        // Then 
        $response->assertStatus(401);
    }
}
