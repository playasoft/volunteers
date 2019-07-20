<?php

namespace Tests\Feature;

use App\Models\Slot;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function admin_assigns_user_releases()
    {
        // Given
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();
        $slot = factory(Slot::class)->create();

        // When
        $assign_response = $this->actingAs($admin)->post("/slot/$slot->id/adminAssign", [
            'user' => $user->id,
        ]);
        $release_response = $this->actingAs($user)->post("/slot/$slot->id/release");

        // Then
        $this->assertDatabaseMissing('slots', [
            'id' => $slot->id,
            'user_id' => $user->id,
        ]);
    }
}
