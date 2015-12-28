<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserUpload;
use App\Events\FileChanged;

class AdminController extends Controller
{
    // All profile functions require admin authentication
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    
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
    function userEdit(User $user, Request $request)
    {
        $user->role = $request->get('role');
        $user->save();
        
        return;
    }

    // List of uploaded files
    function uploadList()
    {
        $uploads = UserUpload::latest()->get();
        return view('pages/admin/upload-list', compact('uploads'));
    }

    // Update information about an uploaded file
    function uploadEdit(UserUpload $upload, Request $request)
    {
        $upload->status = $request->get('status');
        $upload->save();

        event(new FileChanged($upload));

        return;
    }
}
