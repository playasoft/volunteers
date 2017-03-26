<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserUpload;
use App\Events\FileChanged;
use App\Models\Role;
use App\Models\UserRole;

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
        $roles = Role::get();
        $roleNames = [];

        foreach($roles as $role)
        {
            $roleNames[$role->name] = $role->name;
        }

        return view('pages/admin/user-profile', compact('user', 'roleNames'));
    }

    // Update information about a user
    function userEdit(User $user, Request $request)
    {
        $roles = $request->get('roles');

        if($roles)
        {
            UserRole::clear($user);
            UserRole::assign($user, $roles);
        }

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
