<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function ()
{
    if(Auth::check())
    {
        return view('pages/dashboard');
    }
    else
    {
        return view('pages/home');
    }
});

Route::get('/register', function ()
{
    return view('pages/register')->with(Input::get());
});

Route::post('/register', 'UserController@create');

Route::get('/login', function ()
{
    return view('pages/login');
});

Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');


Route::get('/event', 'EventController@get');
