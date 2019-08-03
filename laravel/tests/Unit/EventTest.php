<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Shift;

class EventTest extends TestCase
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
        $shift = factory(Shift::class)->create();
        $event = $shift->event;
        $department = $shift->department;

        // When
        $event->delete();

        // Then
        $this->assertSoftDeleted('events', [
            'id' => $event->id
        ]);
        $this->assertDatabaseMissing('shifts', [
            'id' => $shift->id,
        ]);
        $this->assertSoftDeleted('departments', [
            'id' => $department->id,
        ]);
    }
}
