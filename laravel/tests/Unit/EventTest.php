<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Event;
use App\Models\Schedule;
use App\Models\Slot;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function event_has_schedules()
    {
        // Given
        $event = factory(Event::class)->create();
        factory(Schedule::class, 4)->create([
            'event_id' => $event->id,
        ]);

        // When
        $schedules = $event->schedules;

        // Then 
        $this->assertEquals($schedules->count(),4);
    }

    /**
     * @test
     *
     * @return void
     */
    public function event_has_slots()
    {
        // Given
        $event = factory(Event::class)->create();
        factory(Slot::class, 2)->create([
            'event_id' => $event->id,
        ]);

        // When
        $slots = $event->slots;

        // Then 
        $this->assertEquals($slots->count(),2);
    }
}
