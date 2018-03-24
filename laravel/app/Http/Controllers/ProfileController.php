<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UploadRequest;

use App\Models\User;
use App\Models\UserData;
use App\Models\UserUpload;
use App\Events\FileUploaded;

class ProfileController extends Controller
{
    // All profile functions require authentication
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('bindings');
    }
    
    // View your profile
    function view()
    {
        $user = Auth::user();
        return view('pages/profile/view', compact('user'));
    }

    // View a list of your shifts
    function shifts()
    {
        $user = Auth::user();
        $upcoming = $user->slots()->where('start_date', '>=', Carbon::now()->format('Y-m-d'))
                                    ->orderBy('start_date', 'asc')
                                    ->orderBy('start_time', 'asc')->get();

        $past = $user->slots()->where('start_date', '<', Carbon::now()->format('Y-m-d'))
                                ->orderBy('start_date', 'desc')
                                ->orderBy('start_time', 'desc')->get();

        return view('pages/profile/shifts', compact('user', 'upcoming', 'past'));
    }

    // View page to edit your profile
    function editForm()
    {
        $user = Auth::user();
        return view('pages/profile/edit', compact('user'));
    }

    function dataForm()
    {
        $user = Auth::user();
        return view('pages/profile/edit-data', compact('user'));
    }

    function passwordForm()
    {
        $user = Auth::user();
        return view('pages/profile/edit-password', compact('user'));
    }


    // Handle editing profiles
    function edit(ProfileRequest $request)
    {
        $input = $request->all();
        $user = Auth::user();

        if($input['type'] == 'account')
        {
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->save();
        }
        elseif($input['type'] == 'password')
        {
            $user->password = bcrypt($input['new_password']);
            $user->save();
        }
        elseif($input['type'] == 'data')
        {
            // Remove empty inputs
            $input = array_filter($input);

            // Create new row in user data if none exists
            if(is_null($user->data))
            {
                $data = new UserData();
                $data->user_id = $user->id;
                $data->save();

                $data->update($input);
            }
            else
            {
                $user->data->update($input);
            }
        }

        $request->session()->flash('success', 'Your profile was updated.');
        return redirect('/profile');
    }

    // View page to upload a file
    function uploadForm()
    {
        $user = Auth::user();
        return view('pages/profile/upload', compact('user'));
    }

    // Handle uploading files
    function upload(UploadRequest $request)
    {
        $user = Auth::user();

        // If this user already has at least 5 pending uploads, tell them to wait
        if($user->uploads->where('status', 'pending')->count() >= 5)
        {
            $request->session()->flash('error', "You've already uploaded 5 files. Please wait for an admin to review them before uploading more.");
            return redirect('/profile');
        }
        
        // Create upload folder if it doesn't exist
        if(!file_exists(public_path() . '/files/user'))
        {
            mkdir(public_path() . '/files/user', 0755, true);
        }

        // Make sure the original filename is sanitized
        $file = pathinfo($request->file('file')->getClientOriginalName());
        $fileName = preg_replace('/[^a-z0-9-_]/i', '', $file['filename']) . "." . preg_replace('/[^a-z0-9-_]/i', '', $file['extension']);

        // Move file to uploads directory
        $fileName = time() . '-' . $fileName;
        $request->file('file')->move(public_path() . '/files/user', $fileName);

        // Create a new user upload
        $upload = new UserUpload(); 
        $upload->file = $fileName;
        $upload->status = 'pending';
        $upload->user_id = $user->id;
        $upload->save();

        // Save additional form data
        $input = $request->all();
        $upload->update($input);

        event(new FileUploaded($upload));

        $request->session()->flash('success', 'Your file was uploaded.');
        return redirect('/profile');
    }
}
