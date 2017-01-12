<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Require admin authentication
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Main page for reports
    function reportList()
    {
        $events = Event::orderBy('start_date', 'desc')->take(10)->get();
        return view('pages/admin/report-list', compact('events'));
    }

    function searchUsers(Request $request)
    {
        $search = $request->get('search');
        $users = [];

        if(is_numeric($search))
        {
            $userSearch = [User::find($search)];
        }
        else
        {
            $userSearch = User::where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->take(5)->get();
        }

        foreach($userSearch as $user)
        {
            $users[] =
            [
                'id' => $user->id,
                'name' => $user->name,
                'real_name' => !empty($user->data) ? $user->data->real_name : '',
                'email' => $user->email
            ];
        }

        return json_encode($users);
    }

    function getDepartments(Request $request)
    {
        $id = $request->get('event');
        $event = Event::findOrFail($id);
        $departments = [];

        foreach($event->departments()->orderBy('name', 'asc')->get() as $department)
        {
            $departments[$department->id] = $department->name;
        }

        return json_encode($departments);
    }

    function getDays(Request $request)
    {
        $id = $request->get('event');
        $event = Event::findOrFail($id);
        $days = [];

        foreach($event->days() as $day)
        {
            $day->date = $day->date->format('m/d/Y');
            $days[] = $day;
        }

        return json_encode($days);
    }
}
