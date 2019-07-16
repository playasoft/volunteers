<?php

namespace Tests\Feature;

use App\Models\Slot;
use App\Models\UserData;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotControllerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function shift_warned_on_view()
    {
        $slots = factory(Slot::class, 2)->create();
        $user = factory(UserData::class)->create()->user;

        $this->actingAs($user);

        // When
        $response = $this->post("/slot/{$slots[0]->id}/take");
        $response = $this->get("/slot/{$slots[1]->id}/view");

        // Then
        $response->assertSee("You are currently signed up for another");
    }
}
