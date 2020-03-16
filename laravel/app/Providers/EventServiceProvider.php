<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen =
    [
        'App\Events\UserRegistered' =>
        [
            'App\Listeners\SendUserWelcome',
            'App\Listeners\SendAdminWelcome',
        ],

        'App\Events\FileUploaded' =>
        [
            'App\Listeners\SendAdminFileUploaded',
        ],

        'App\Events\FileChanged' =>
        [
            'App\Listeners\SendUserFileChanged',
        ],

        'App\Events\ForgotPassword' =>
        [
            'App\Listeners\SendUserMessage',
        ],
        'App\Events\SlotChanged' =>
        [
            'App\Listeners\SendAdminRemovedShift',
            'App\Listeners\SendUserShiftConfirmation',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
