<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Slot;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CascadingSoftDeletesTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function event_soft_delete_cascades()
    {
        // Given
        $slot = factory(Slot::class)->create();
        $event = $slot->schedule->department->event;
        $department = $slot->schedule->department;
        $shift = $slot->schedule->shift;
        $schedule = $slot->schedule;

        // dd($department);

        // When
        $slot->event->delete();

        // Then
        //soft deleted
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
        ]);
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
            'deleted_at' => null,
        ]);

        //soft deleted
        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
        ]);
        $this->assertDatabaseMissing('departments', [
            'id' => $department->id,
            'deleted_at' => null,
        ]);

        //deleted
        $this->assertDatabaseMissing('shifts', [
            'id' => $shift->id,
        ]);

        //deleted, since the shift is deleted
        $this->assertDatabaseMissing('schedule', [
            'id' => $schedule->id,
        ]);

        //deleted
        $this->assertDatabaseMissing('slots', [
            'id' => $slot->id,
        ]);
    }
}
