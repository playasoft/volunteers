<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    // Create a new user
    public function create(UserRequest $request)
    {
        $input = $request->all();

        $user = new User;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->save();

        $request->session()->flash('success', 'Your account has been registered, you may now log in.');
        return redirect('/login');
    }

    // Log a user in
    public function login(UserRequest $request)
    {
        $user = User::where('name', $request->get('name'))->get()[0];
        $request->session()->put('user', $user);

        $request->session()->flash('success', 'You are now logged in!');
        return redirect('/');
    }

    // Log a user out
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->flash('success', 'You are now logged out!');
        return redirect('/');
    }
}
