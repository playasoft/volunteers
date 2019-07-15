<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Slot;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CascadeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function cascade_delete()
    {
        // Given
        $slot = factory(Slot::class)->create(); //bottom child
        $schedule = $slot->schedule;
        $shift = $slot->schedule->shift;
        $department = $slot->schedule->department;
        $event = $slot->schedule->department->event; //top parent

        // When
        $event->delete();

        // Then
        $this->assertSoftDeleted('events', [
            'id' => $event->id,
        ]);

        $this->assertSoftDeleted('departments', [
            'id' => $department->id,
        ]);

        $this->assertDatabaseMissing('shifts', [
            'id' => $shift->id,
        ]);

        $this->assertDatabaseMissing('schedule', [
            'id' => $schedule->id,
        ]);


        $this->assertDatabaseMissing('slots', [
            'id' => $slot->id,
        ]);
    }
}
