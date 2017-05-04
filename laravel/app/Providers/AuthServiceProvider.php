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
            'view-self',
        ],
        
        'admin' =>
        [
            'create-event',
            'edit-event',
            'delete-event',
            'read-department',
            'create-department',
            'edit-department',
            'delete-department',
            'read-shift',
            'create-shift',
            'edit-shift',
            'delete-shift',
            'create-schedule',
            'edit-schedule',
            'delete-schedule',
            'view-user',
            'edit-user',
        ],

        'department-lead' =>
        [
            'create-schedule',
            'edit-schedule',
            'delete-schedule',
            'read-department',
            'read-shift',
            'edit-shift',
            'create-shift',
        ],

        'volunteer' =>
        [
            // No special permissions
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
                // Always include default "auth" role
                if(in_array('auth', $roles))
                {
                    return true;
                }

                foreach($user->roles as $userRole)
                {
                    if(in_array($userRole->role->name, $roles))
                    {
                        return true;
                    }
                }
            });
        }

        return false;
    }
}
