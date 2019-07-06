<?php

namespace Tests\Unit;

use Artisan;
use Tests\TestCase;
use App\Models\Notification;
use App\Models\User;
use App\Models\Slot;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function notification_persists_to_db()
    {
        $notification = factory(Notification::class)->create();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function slot_notifications_stored()
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
    public function email_notification_is_sent()
    {
        $user = factory(User::class)->create();
        $slot = factory(Slot::class)->create();

        $response = $this->actingAs($user)->post("/slot/$slot->id/take");

        $exit_code = Artisan::call('notifications:send');

        $notification = Notification::where('user_to', $user->id)->first();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status' => 'sent'
        ]);
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
