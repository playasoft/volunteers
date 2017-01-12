<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

    function searchUsers()
    {
        
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
