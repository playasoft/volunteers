<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    // View your profile
    function view()
    {
        return view('pages/profile/view');
    }

    // Edit your profile
    function edit()
    {
        return view('pages/profile/edit');
    }

    // View a list of your shifts
    function shifts()
    {
        return view('pages/profile/shifts');
    }
}
