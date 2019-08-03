<?php

namespace Tests\Feature;

use App\Models\Slot;
use App\Models\UserData;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotControllerTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function same_time_shift_warned_on_view()
    {
        // Given
        $take_slot = factory(Slot::class)->create();
        $view_slot = factory(Slot::class)->create([
            'start_date' => $take_slot->start_date,
            'start_time' => $take_slot->start_time,
            'end_time' => $take_slot->end_time,
        ]);
        $user = factory(UserData::class)->create()->user;

        $take_slot->user_id = $user->id;
        $take_slot->save();

        $this->actingAs($user);

        // When
        $response = $this->get("/slot/{$view_slot->id}/view");

        // Then
        $response->assertSee("You are currently signed up for another")
                ->assertSee("overlapping shift");
    }

    /**
     * @test
     *
     * @return void
     */
    public function same_time_shift_warned_on_admin_assign()
    {
        // Given
        $take_slot = factory(Slot::class)->create();
        $view_slot = factory(Slot::class)->create([
            'start_date' => $take_slot->start_date,
            'start_time' => $take_slot->start_time,
            'end_time' => $take_slot->end_time,
        ]);
        $user = factory(UserData::class)->create()->user;
        $admin = factory(UserData::class)->create([
            'user_id' => factory(User::class)->states('admin')->create()->id,
        ])->user;

        $this->actingAs($admin);

        // When
        $take_slot->user_id = $user->id;
        $take_slot->save();

        $response = $this->followingRedirects()->post("/slot/{$view_slot->id}/adminAssign", [
            'user' => $user->id,
        ]);

        // Then
        $response->assertSee("Are you sure you want to sign them up for this shift?");
    }

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
