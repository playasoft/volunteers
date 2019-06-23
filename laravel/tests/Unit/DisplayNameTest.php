<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Helpers;
use App\Models\UserData;
use App\Models\User;

class DisplayNameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function display_name_is_burner_name_if_the_burner_name_is_not_an_empty_string()
    {
        // Given
        $user_burner_name = 'HellaMan';

        $user = $this->factoryWithSetup(User::class)->create();
        $user->data()->save(factory(UserData::class)->make([
            'burner_name' => $user_burner_name,
        ]));

        // When
        $display_name = Helpers::displayName($user);

        // Then
        $this->assertEquals($display_name, $user_burner_name);
    }

    /**
     * @test
     *
     * @return void
     */
    public function display_name_is_user_name_when_burner_name_is_an_empty_string()
    {
        // Given
        $user_name = 'hartio.monte27';

        $user = $this->factoryWithSetup(User::class)->create([
            'name' => $user_name,
        ]);
        $user->data()->save(factory(UserData::class)->make([
            'burner_name' => '',
        ]));

        // When
        $display_name = Helpers::displayName($user);

        // Then
        $this->assertEquals($display_name, $user_name);
    }

    /**
     * @test
     *
     * @return void
     */
    public function display_name_is_user_name_when_burner_name_does_not_exist()
    {
        // Given
        $user_name = 'hartio.monte27';

        $user = $this->factoryWithSetup(User::class)->create([
            'name' => $user_name,
        ]);
        $user->data()->save(factory(UserData::class)->make([
            'burner_name' => null,
        ]));

        // When
        $display_name = Helpers::displayName($user);

        // Then
        $this->assertEquals($display_name, $user_name);
    }

    /**
     * @test
     *
     * @return void
     */
    public function display_name_is_user_name_when_user_data_does_not_exist()
    {
        // Given
        $user_name = 'hartio.monte27';

        $user = $this->factoryWithSetup(User::class)->create([
            'name' => $user_name,
        ]);

        // When
        $display_name = Helpers::displayName($user);

        // Then
        $this->assertEquals($display_name, $user_name);
    }

    /**
     * @test
     *
     * @return void
     */
    public function display_name_is_alternative_name_when_no_user_is_given()
    {
        // Given
        $alternative_name = 'The User';
        $user = null;

        // When
        $display_name = Helpers::displayName($user, $alternative_name);

        // Then
        $this->assertEquals($display_name, $alternative_name);
    }
}
