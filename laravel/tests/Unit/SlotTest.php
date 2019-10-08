<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Slot;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function slot_has_event()
    {
        // Given
        $slot = factory(Slot::class)->create();
        
        // When  
        $event = $slot->event;

        // Then 
        $this->assertNotNull($event);
    }

    /**
     * @test
     *
     * @return void
     */
    public function slot_can_be_created_without_event_id()
    {
        // Given
        $faker_slot = factory(Slot::class)->create();
        
        // When
        $faker = $faker_slot->toArray();
        unset($faker['event_id']);
        $slot = Slot::create($faker);

        // Then 
        $this->assertDatabaseHas('slots', [
            'id' => $slot->id,
            'event_id' => $faker_slot->event_id,            
        ]);
    }
}
