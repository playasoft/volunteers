<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Slot;
use Carbon\Carbon;
use App\Jobs\DeleteOldSlotsJob;

class DeleteOldSlotsJobTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     *
     * @return void
     */
    public function deletes_old_slot()
    {
        // Given
        $yesterday = Carbon::yesterday();
        $slot = factory(Slot::class)->create([
            'start_date' => $yesterday->format('Y-m-d'),
        ]);

        // When
        DeleteOldSlotsJob::dispatchNow();

        // Then
        $this->assertDatabaseMissing('slots', [
            'id' => $slot->id,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function deletes_old_slot_hour_before()
    {
        // Given
        $hour_before = Carbon::now()->subHour();
        $slot = factory(Slot::class)->create([
            'start_date' => $hour_before->format('Y-m-d'),
            'end_time' => $hour_before->format('H:i:s'),
            'start_time' => $hour_before->subHour()->format('H:i:s'),
        ]);

        // When
        DeleteOldSlotsJob::dispatchNow();

        // Then
        $this->assertDatabaseMissing('slots', [
            'id' => $slot->id,
        ]);
    }
}
