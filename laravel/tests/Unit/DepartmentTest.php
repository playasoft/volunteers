<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Shift;

class DepartmentTest extends TestCase
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
        $department = $shift->department;

        // When
        $department->delete();

        // Then
        $this->assertSoftDeleted('departments', [
            'id' => $department->id
        ]);
        $this->assertDatabaseMissing('shifts', [
            'id' => $shift->id,
        ]);
    }
}
