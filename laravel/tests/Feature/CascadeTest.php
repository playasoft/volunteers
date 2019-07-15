<?php

namespace Tests\Feature;

use Carbon\Carbon;
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
    public function cascade_update_publish_at()
    {
        // Given
        $slot = factory(Slot::class)->create();
        $schedule = $slot->schedule;
        $shift = $slot->schedule->shift;
        $department = $slot->schedule->department;
        $event = $slot->schedule->department->event;

        $publish_time = Carbon::now();

        // When
        $event->published_at = $publish_time;
        $event->save();

        // Then
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'published_at' => $publish_time,
        ]);
        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'published_at' => $publish_time,
        ]);
        $this->assertDatabaseHas('shifts', [
            'id' => $shift->id,
            'published_at' => $publish_time,
        ]);
        $this->assertDatabaseHas('schedule', [
            'id' => $schedule->id,
            'published_at' => $publish_time,
        ]);
        $this->assertDatabaseHas('slots', [
            'id' => $slot->id,
            'published_at' => $publish_time,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function cascade_delete_event()
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
    }

    public function cascade_delete_department()
    {
        // Given
        $slot = factory(Slot::class)->create(); //bottom child
        $schedule = $slot->schedule;
        $shift = $slot->schedule->shift;
        $department = $slot->schedule->department;

        // When
        $department->delete();

        // Then
        $this->assertSoftDeleted('departments', [
            'id' => $department->id,
        ]);
        $this->assertDatabaseMissing('shifts', [
            'id' => $shift->id,
        ]);
        $this->assertDatabaseMissing('schedule', [
            'id' => $schedule->id,
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function cascade_delete_shift()
    {
        // Given
        $slot = factory(Slot::class)->create(); //bottom child
        $schedule = $slot->schedule;
        $shift = $slot->schedule->shift;

        // When
        $shift->delete();

        // Then
        $this->assertDatabaseMissing('shifts', [
            'id' => $shift->id,
        ]);
        $this->assertDatabaseMissing('schedule', [
            'id' => $schedule->id,
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function cascade_delete_schedule()
    {
        // Given
        $slot = factory(Slot::class)->create(); //bottom child
        $schedule = $slot->schedule;

        // When
        $schedule->delete();

        // Then
        $this->assertSoftDeleted('schedule', [
            'id' => $schedule->id,
        ]);
        $this->assertDatabaseMissing('slots', [
            'id' => $slot->id,
        ]);
    }
}
