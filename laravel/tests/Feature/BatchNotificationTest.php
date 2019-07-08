<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Slot;
use App\Models\Notification;
use Aritsan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchNotificationTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function slot_notification_stored()
    {
        $user = factory(User::class)->create();
        $slot = factory(Slot::class)->create();

        $response = $this->actingAs($user)->post("/slot/$slot->id/take");

        $this->assertDatabaseHas('notifications', [
            'user_to' => $user->id,
        ]);

        $metadata = json_decode(Notification::where('user_to', $user->id)->first()->metadata);

        $this->assertEquals($metadata->slot_id,$slot->id);
    }

    /**
     * @test
     * @return void
     */
    public function send_notifications_in_batch()
    {
        $user = factory(User::class)->create();
        $slots = factory(Slot::class, 5)->create();

        $slots->each(function($slot) use ($user) {
            $this->actingAs($user)->post("/slot/$slot->id/take");
        });

        Artisan::call('notifications:send');

        $user_notifications = Notification::where('user_to', $user->id)->where('status', 'sent');

        $this->assertEquals($user_notifications->count(), $slots->count());
    }
}
