<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
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
        Validator::extend('hashed', function($attribute, $value, $parameters)
        {
            $user = User::where('name', Input::get('name'))->get();

            if($user->count())
            {
                $user = $user[0];
                
                if(Hash::check($value, $user->password))
                {
                    return true;
                }
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
