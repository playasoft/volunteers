<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies =
    [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Manually defined array of what abilities given roles have
     *
     * @var array
     */
    private $roles =
    [
        // General permissions for all authed users
        'auth' =>
        [
            'take-shift',
            'release-shift'
        ],
        
        'admin' =>
        [
            'create-events',
            'edit-events',
            'create-departments',
            'edit-departments',
            'create-shifts',
            'edit-shifts',
            'view-users',
            'edit-users'
        ],

        'volunteer' =>
        [
            // No special permissions
        ],

        'veteran' =>
        [
            'create-shifts',
            'edit-shifts',
            'take-veteran-shift',
        ],

        'medical' =>
        [
            'take-medical-shift',
        ],

        'fire' =>
        [
            'take-fire-shift',
        ]
    ];

    /**
     * Automatically generated array of abilities and roles
     *
     * @var array
     */
    private $abilities = [];


    /**
     * Function to generate a formatted array of abilities and roles
     *
     * @param array $roles
     * @return array
     */
    private function generateAbilities()
    {
        foreach($this->roles as $role => $abilities)
        {
            foreach($abilities as $ability)
            {
                if(isset($this->abilities[$ability]))
                {
                    $this->abilities[$ability][] = $role;
                }
                else
                {
                    $this->abilities[$ability] = [$role];
                }
            }
        }
    }

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        $this->generateAbilities();

        // Loop through generated abilities and create gate policies
        foreach($this->abilities as $ability => $roles)
        {
            $gate->define($ability, function($user) use ($roles)
            {
                if(in_array($user->role, $roles))
                {
                    return true;
                }
            });
        }

        return false;
    }
}
