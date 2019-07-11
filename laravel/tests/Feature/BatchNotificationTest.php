<?php

namespace Tests\Feature;

use App\Jobs\SendUserMailJob;
use App\Models\Notification;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function slot_notification_stored()
    {
        // Given
        $user = factory(User::class)->create();
        $slot = factory(Slot::class)->create();

        // When
        $response = $this->actingAs($user)->post("/slot/$slot->id/take");

        // Then
        $this->assertDatabaseHas('notifications', [
            'user_to' => $user->id,
            'metadata->slot_id' => $slot->id,
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function send_notifications_in_batch()
    {
        // Given
        $user = factory(User::class)->create();
        $slots = factory(Slot::class, 5)->create();

        // When
        $this->actingAs($user); //act as the user dawg
        $slots->each(function ($slot) use ($user)
        {
            $this->post("/slot/$slot->id/take");
        });
        SendUserMailJob::dispatchNow();

        // Then
        $user_notifications = Notification::where('user_to', $user->id)
                                ->where('status', 'sent');
        $this->assertEquals($user_notifications->count(), $slots->count());
    }

    /**
     * @test
     * @return void
     */
    public function cancel_slot_notification()
    {
        // Given
        $user = factory(User::class)->create();
        $slot = factory(Slot::class)->create();

        // When
        $this->actingAs($user);
        // take it
        $this->post("/slot/$slot->id/take");
        // drop it
        $response = $this->post("/slot/$slot->id/release");
        //bop it
        SendUserMailJob::dispatchNow();

        // Then
        //check it
        $this->assertDatabaseHas('notifications', [
            'user_to' => $user->id,
            'status' => 'canceled',
        ]);
    }
}
