<?php

namespace App\Http\Controllers;

// Laravel
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

// Custom
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Events\UserRegistered;
use App\Events\ForgotPassword;
use Carbon\Carbon;
use App\Models\Event;

class UserController extends Controller
{
    // Create a new user
    public function create(UserRequest $request)
    {
        // Create user based on post input
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Is this the first user?
        if($user->id == 1)
        {
            $user->role = 'admin';
        }
        else
        {
            // Otherwise assign to volunteer role by default
            $user->role = 'volunteer';
        }

        $user->save();
        $this->auth->loginUsingID($user->id);

        // Send notification emails
        event(new UserRegistered($user));

        $request->session()->flash('success', 'Your account has been registered, you are now logged in.');
        return redirect('/');
    }

    // Handle a user logging in
    public function login(UserRequest $request)
    {
        $credentials = array
        (
            'name' => $request->get('name'),
            'password' => $request->get('password')
        );

        if($this->auth->attempt($credentials))
        {
            $request->session()->flash('success', 'You are now logged in!');
        }

        // Check if there's an ongoing or upcoming event to redirect the user to
        $event = Event::ongoingOrUpcoming();

        if(!empty($event))
        {
            return redirect('/event/' . $event->id);
        }

        // Fall back to the main page
        return redirect('/');
    }

    // Log a user out
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->flash('success', 'You are now logged out!');
        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $input = $request->all();

        // Does the input match a registered username or email?
        $user = User::where('name', $input['user'])->orWhere('email', $input['user'])->first();

        if(!$user)
        {
            $request->session()->flash('error', 'No user was found with that information.');
            return back();
        }

        // When was the last reset time?
        if(!is_null($user->reset_time))
        {
            // Don't allow resets more than once per day
            if($user->reset_time->diffInDays(Carbon::now()) < 1)
            {
                $request->session()->flash('error', "Your password has already been reset today.");
                return back();
            }
        }

        // Generate a random reset token
        $user->reset_token = bin2hex(openssl_random_pseudo_bytes(8));

        // Update the reset timestamp
        $user->reset_time = Carbon::now();
        $user->save();

        // Trigger user notification
        event(new ForgotPassword($user));

        $request->session()->flash('success', 'A reset code has been sent to your email.');
        return back();
    }

    public function verifyToken(Request $request, $token)
    {
        $yesterday = date('Y-m-d H:i:s', strtotime("24 hours ago"));

        // Only select matching tokens from the past day
        $user = User::where('reset_token', $token)->where('reset_time', '>=', $yesterday)->first();

        if(!$user)
        {
            $request->session()->flash('error', 'Invalid reset code. It may have expired.');
            return redirect('/forgot');
        }

        return view('pages/forgot', compact('token', 'user'));
    }

    public function changePassword(UserRequest $request, $token)
    {
        $input = $request->all();
        $yesterday = date('Y-m-d H:i:s', strtotime("24 hours ago"));

        // Only select matching tokens from the past day
        $user = User::where('reset_token', $token)->where('reset_time', '>=', $yesterday)->first();

        if(!$user)
        {
            $request->session()->flash('error', 'Invalid reset code. It may have expired.');
            return redirect('/forgot');
        }

        $user->password = bcrypt($input['password']);
        $user->save();

        $request->session()->flash('success', 'Your password has been reset, you may now log in.');
        return redirect('/login');
    }
}
