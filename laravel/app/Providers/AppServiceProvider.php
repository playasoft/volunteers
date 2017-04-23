<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('account', function($attribute, $value, $parameters)
        {
            $user = User::where('name', Input::get('name'))->orWhere('email', Input::get('name'))->first();

            if(!is_null($user))
            {
                return true;
            }

            return false;
        });

        Validator::extend('hashed', function($attribute, $value, $parameters)
        {
            // If we're already logged in
            if(Auth::check())
            {
                $user = Auth::user();
            }
            else
            {
                // Otherwise, try to get the username from form input
                $user = User::where('name', Input::get('name'))->orWhere('email', Input::get('name'))->first();

                if(is_null($user))
                {
                    return false;
                }
            }

            if(Hash::check($value, $user->password))
            {
                return true;
            }
            
            return false;
        });

        Validator::extend('time', function($attribute, $value, $parameters)
        {
            $value = trim($value);
            
            // Check against 12 hour time (with AM/PM) or 24 hour time
            $twelve = date_parse_from_format('h:i a', $value);
            $twentyfour = date_parse_from_format('H:i', $value);

            if($twelve['error_count'] === 0 || $twentyfour['error_count'] === 0)
            {
                return true;
            }

            return false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
