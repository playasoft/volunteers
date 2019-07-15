<?php

namespace Tests\Feature;

use App\Models\Slot;
use App\Models\UserData;
use App\Models\Role;
use App\Models\UserRole;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckPublishedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function can_view_unpublished_slot_as_department_lead()
    {
        // Given
        $slot = factory(Slot::class)->create();
        $user = factory(UserData::class)->states('department-lead')->create()->user;

        $this->actingAs($user);

        // When
        $response = $this->get("/slot/$slot->id/view");

        // Then
        $response->assertStatus(200);
    }

    /**
     * @test
     *
     * @return void
     */
    public function cannot_view_unpublished_slot_as_non_department_lead()
    {
        // Given
        $slot = factory(Slot::class)->create();
        $user = factory(UserData::class)->create()->user;

        $this->actingAs($user);

        // When
        $response = $this->get("/slot/$slot->id/view");

        // Then
        $response->assertStatus(401);
    }
}
