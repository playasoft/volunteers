<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Slot;
use App\Models\Notification;
use Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchNotificationTest extends TestCase
{
    use RefreshDatabase;

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

        $metadata = Notification::where('user_to', $user->id)->first()->metadata;

        $this->assertEquals($metadata['slot_id'],$slot->id);
    }

    /**
     * @test
     * @return void
     */
    public function send_notifications_in_batch()
    {
        $user = factory(User::class)->create();
        $slots = factory(Slot::class, 5)->create();

        $this->actingAs($user); //act as the user dawg

        $slots->each(function($slot) use ($user) {
            $this->post("/slot/$slot->id/take");
        });

        Artisan::call('notifications:send');

        $user_notifications = Notification::where('user_to', $user->id)->where('status', 'sent');

        $this->assertEquals($user_notifications->count(), $slots->count());
    }

    /**
     * @test
     * @return void
     */
    public function cancel_slot_notification()
    {
        $user = factory(User::class)->create();
        $slot = factory(Slot::class)->create();

        $this->actingAs($user);

        // take it
        $this->post("/slot/$slot->id/take");
        // drop it
        $response = $this->post("/slot/$slot->id/release");
        //bop it
        Artisan::call('notifications:send');

        //check it
        $this->assertDatabaseHas('notifications', [
            'user_to' => $user->id,
            'status' => 'canceled',
        ]);

    }
}
