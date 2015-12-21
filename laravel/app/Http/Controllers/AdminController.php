<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // List of users
    function userList()
    {
        return view('pages/admin/user-list');
    }

    // View an indivual user profile
    function userProfile()
    {
        return view('pages/admin/user-profile');
    }

    // Update information about a user
    function userEdit()
    {
        return "// todo";
    }

    // List of uploaded files
    function uploadList()
    {
        return view('pages/admin/user-uploads');
    }

    // Update information about an uploaded file
    function uploadEdit()
    {
        return "// todo";
    }
}
