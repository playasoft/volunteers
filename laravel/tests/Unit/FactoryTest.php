<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Event;
use App\Models\EventRole;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Slot;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserRole;
use App\Models\UserUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function department_factory_is_working()
    {
        $department = $this->factoryWithSetup(Department::class)->create();
        $this->assertNotNull($department);
    }

    /**
     * @test
     *
     * @return void
     */
    public function event_factory_is_working()
    {
        $event = $this->factoryWithSetup(Event::class)->create();
        $this->assertNotNull($event);
    }

    /**
     * @test
     *
     * @return void
     */
    public function event_role_factory_is_working()
    {
        $event_role = $this->factoryWithSetup(EventRole::class)->create();
        $this->assertNotNull($event_role);
    }

    /**
     * @test
     *
     * @return void
     */
    public function role_factory_is_working()
    {
        $role = $this->factoryWithSetup(Role::class)->create();
        $this->assertNotNull($role);
    }

    /**
     * @test
     *
     * @return void
     */
    public function schedule_factory_is_working()
    {
        $schedule = $this->factoryWithSetup(Schedule::class)->create();
        $this->assertNotNull($schedule);
    }

    /**
     * @test
     *
     * @return void
     */
    public function shift_factory_is_working()
    {
        $shift = $this->factoryWithSetup(Shift::class)->create();
        $this->assertNotNull($shift);
    }

    /**
     * @test
     *
     * @return void
     */
    public function slot_factory_is_working()
    {
        $slot = $this->factoryWithSetup(Slot::class)->create();
        $this->assertNotNull($slot);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_factory_is_working()
    {
        $user = $this->factoryWithSetup(User::class)->create();
        $this->assertNotNull($user);
        $this->assertTrue($user->roles->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_data_factory_is_working()
    {
        $user_data = $this->factoryWithSetup(UserData::class)->create();
        $this->assertNotNull($user_data);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_role_factory_is_working()
    {
        $user_role = $this->factoryWithSetup(UserRole::class)->create();
        $this->assertNotNull($user_role);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_upload_factory_is_working()
    {
        $user_upload = $this->factoryWithSetup(UserUpload::class)->create();
        $this->assertNotNull($user_upload);

    }
}
