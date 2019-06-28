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

/**
 * Trivial test case to make sure that all factories
 * are running with "with_setup" states that properly
 * assign foreign key dependencies.
 */
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
        $department = factoryWithSetup(Department::class)->create();
        $this->assertNotNull($department);
    }

    /**
     * @test
     *
     * @return void
     */
    public function event_factory_is_working()
    {
        $event = factoryWithSetup(Event::class)->create();
        $this->assertNotNull($event);
    }

    /**
     * @test
     *
     * @return void
     */
    public function event_role_factory_is_working()
    {
        $event_role = factoryWithSetup(EventRole::class)->create();
        $this->assertNotNull($event_role);
    }

    /**
     * @test
     *
     * @return void
     */
    public function role_factory_is_working()
    {
        $role = factoryWithSetup(Role::class)->create();
        $this->assertNotNull($role);
    }

    /**
     * @test
     *
     * @return void
     */
    public function schedule_factory_is_working()
    {
        $schedule = factoryWithSetup(Schedule::class)->create();
        $this->assertNotNull($schedule);
    }

    /**
     * @test
     *
     * @return void
     */
    public function shift_factory_is_working()
    {
        $shift = factoryWithSetup(Shift::class)->create();
        $this->assertNotNull($shift);
    }

    /**
     * @test
     *
     * @return void
     */
    public function slot_factory_is_working()
    {
        $slot = factoryWithSetup(Slot::class)->create();
        $this->assertNotNull($slot);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_factory_is_working()
    {
        $user = factoryWithSetup(User::class)->create();
        $this->assertNotNull($user);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_data_factory_is_working()
    {
        $user_data = factoryWithSetup(UserData::class)->create();
        $this->assertNotNull($user_data);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_role_factory_is_working()
    {
        $user_role = factoryWithSetup(UserRole::class)->create();
        $this->assertNotNull($user_role);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_upload_factory_is_working()
    {
        $user_upload = factoryWithSetup(UserUpload::class)->create();
        $this->assertNotNull($user_upload);
    }
}
