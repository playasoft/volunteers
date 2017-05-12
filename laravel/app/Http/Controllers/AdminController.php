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
    function userList(Request $request)
    {
        if(!empty($request->query()['userPageLimit'])){
            session(['userPageLimit' => $request->query()['userPageLimit']]);
        }

        $userPageLimit = $request->session()->get('userPageLimit',25);
        $users = User::latest()->paginate($userPageLimit); 

        return view('pages/admin/user-list', ['users' => $users, 'pageLimit' => $userPageLimit]);
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
    function uploadList(Request $request)
    {
        if(!empty($request->query()['userPageLimit'])){
            session(['userPageLimit' => $request->query()['userPageLimit']]);
        }

        $userPageLimit = $request->session()->get('userPageLimit',25);
        $uploads =  UserUpload::latest()->paginate($userPageLimit); 

        return view('pages/admin/upload-list', ['uploads' => $uploads, 'pageLimit' => $userPageLimit]);
    }

    // Update information about an uploaded file
    function uploadEdit(UserUpload $upload, Request $request)
    {
        $upload->status = $request->get('status');
        $upload->save();

        // A map of statuses to user roles
        $statusMap =
        [
            'approved-medical' => 'medical',
            'approved-fire' => 'fire',
            'approved-ranger' => 'ranger'
        ];

        // If an admin sets an uploaded document to be approved
        if(isset($statusMap[$upload->status]))
        {
            // Assign them to their approved role
            UserRole::assign($upload->user, $statusMap[$upload->status]);
        }

        event(new FileChanged($upload));

        return;
    }
}
