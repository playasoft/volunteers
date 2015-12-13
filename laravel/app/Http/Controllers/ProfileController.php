<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // View your profile
    function view()
    {
        $user = Auth::user();
        return view('pages/profile/view', compact('user'));
    }

    // Edit your profile
    function edit()
    {
        return view('pages/profile/edit');
    }

    // Upload a file
    function upload()
    {
        return view('pages/profile/upload');
    }

    // View a list of your shifts
    function shifts()
    {
        return view('pages/profile/shifts');
    }
}
