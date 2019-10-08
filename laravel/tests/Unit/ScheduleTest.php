<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Schedule;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function schedule_has_event()
    {
        // Given
        $schedule = factory(Schedule::class)->create();
        
        // When  
        $event = $schedule->event;

        // Then 
        $this->assertNotNull($event);
    }

    /**
     * @test
     *
     * @return void
     */
    public function schedule_can_be_created_without_event_id()
    {
        // Given
        $faker_schedule = factory(Schedule::class)->create();
        
        // When
        $faker = $faker_schedule->toArray();
        unset($faker['event_id']);
        $schedule = Schedule::create($faker);

        // Then 
        $this->assertDatabaseHas('schedule', [
            'id' => $schedule->id,
            'event_id' => $faker_schedule->event_id,            
        ]);
    }
}
