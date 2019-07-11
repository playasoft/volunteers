<?php

namespace Tests\Unit;

use App\Jobs\SendUserMailJob;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

        SendUserMailJob::dispatchNow();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status' => 'sent',
        ]);
    }
}
