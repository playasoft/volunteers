<?php

namespace App\Providers;

use \Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::model('event', \App\Models\Event::class);
        Route::model('department', \App\Models\Department::class);
        Route::model('shift', \App\Models\Shift::class);
        Route::model('schedule', \App\Models\Schedule::class);
        Route::model('slot', \App\Models\Slot::class);
        Route::model('user', \App\Models\User::class);
        Route::model('upload', \App\Models\UserUpload::class);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function ()
        {
            require app_path('Http/routes.php');
        });
    }
}
