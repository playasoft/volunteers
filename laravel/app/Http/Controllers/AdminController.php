<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserUpload;

class AdminController extends Controller
{
    // List of users
    function userList()
    {
        $users = User::latest()->get();
        return view('pages/admin/user-list', compact('users'));
    }

    // View an indivual user profile
    function userProfile(User $user)
    {
        return view('pages/admin/user-profile', compact('user'));
    }

    // Update information about a user
    function userEdit(User $user)
    {
        return "// todo";
    }

    // List of uploaded files
    function uploadList()
    {
        $uploads = UserUpload::latest()->get();
        return view('pages/admin/upload-list', compact('uploads'));
    }

    // Update information about an uploaded file
    function uploadEdit()
    {
        return "// todo";
    }
}
