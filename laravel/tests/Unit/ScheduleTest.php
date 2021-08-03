<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Slot;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function soft_delete_cascades()
    {
        // Given
        $slot = factory(Slot::class)->create();
        $schedule = $slot->schedule;

        // When
        $schedule->delete();

        // Then
        $this->assertSoftDeleted('schedule', [
            'id' => $schedule->id
        ]);
        $this->assertDatabaseMissing('slots', [
            'id' => $slot->id,
        ]);
    }
}
