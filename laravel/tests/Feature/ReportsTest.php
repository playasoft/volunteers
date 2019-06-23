<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Department;
use App\Models\User;
use App\Models\Slot;
use ErrorException;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function get_printable_department_reports()
    {
        // $this->expectException(ErrorException::class);

        // Given
        $admin = $this->factoryWithSetup(User::class)->states('admin')->create();
        $this->actingAs($admin);

        $department = $this->factoryWithSetup(Department::class)->create();
        $slots = $this->factoryWithSetup(Slot::class,7)->create();

        $slots->each(function ($slot) use ($department) {
            $slot->schedule->department_id = $department->id;
            $slot->schedule->save();
        });

        // With
        $response = $this->post('/report/generate', [
            'event' => $department->event->id,
            'type' => 'department',
            'department-output' => 'printable'
        ]);

        // Then
        if($response->exception)
        {
            throw $response->exception;
        }
        // dd($response->exception);
        $response->assertStatus(200);
    }
}
