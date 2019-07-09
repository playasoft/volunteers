<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Notification;
use Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

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
    public function email_notification_is_sent()
    {
        $notification = factory(Notification::class)->create([
            'type' => 'email',
        ]);

        Artisan::call('notifications:send');

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status' => 'sent',
        ]);
    }
}
