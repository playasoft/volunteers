<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Middleware\CheckPublished;
use App\Models\Slot;
use App\Models\Event;

class CheckPublishedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function handle_parses_model_name()
    {
        // Given 
        $slot = factory(Slot::class)->create();

        // When 
        $event = CheckPublished::childModelToEvent($slot);

        // Then 
        $this->assertEquals(Event::class, get_class($event));
    }
}
