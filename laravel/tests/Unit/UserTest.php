<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Helpers;
use App\Models\UserData;

class UserTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function display_name_is_full_name_when_no_burner_name_exists()
    {
        // Given
        $user_full_name = 'Hartio Montenegro';

        $user = $this->factoryWithSetup(UserData::class)->create([
            'full_name' => $user_full_name,
            'burner_name' => '',
        ]);

        // When
        $display_name = Helpers::displayName($user);

        // Then
        $this->assertEquals($display_name, $user_full_name);
    }
}
