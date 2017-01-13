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
    public function reportList()
    {
        $events = Event::orderBy('start_date', 'desc')->take(10)->get();
        return view('pages/admin/report-list', compact('events'));
    }

    // TODO: Use a real search engine like ElasticSearch or SOLR
    public function searchUsers(Request $request)
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
                'real_name' => count($user->data) ? $user->data->real_name : '',
                'email' => $user->email
            ];
        }

        return json_encode($users);
    }

    public function getDepartments(Request $request)
    {
        $id = $request->get('event');
        $event = Event::findOrFail($id);
        $departments = [];

        foreach($event->departments()->orderBy('name', 'asc')->get() as $department)
        {
            $departments[] =
            [
                'id' => $department->id,
                'name' => $department->name
            ];
        }

        return json_encode($departments);
    }

    public function getDays(Request $request)
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

    public function generateReport(Request $request)
    {
        $event = Event::find($request->get('event'));
        $type = $request->get('type');

        if(empty($event))
        {
            $request->session()->flash('error', 'An event must be selected to generate reports.');
            return redirect()->back();
        }

        if(empty($type))
        {
            $request->session()->flash('error', 'A report type must be must be selected.');
            return redirect()->back();
        }

        if($type == 'user')
        {
            return $this->userReport($event, $request);
        }
        elseif($type == 'department')
        {
            return $this->departmentReport($event, $request);
        }
        elseif($type == 'day')
        {
            return $this->dayReport($event, $request);
        }
        elseif($type == 'misc')
        {
            $report = $request->get('misc-reports');

            if(empty($report))
            {
                $request->session()->flash('error', 'You must select a report to generate.');
                return redirect()->back();
            }

            if($report == 'hours-volunteered')
            {
                $this->hoursVolunteeredReport($event, $request);
            }
            elseif($report == 'shifts-filled')
            {
                $this->shiftsFilledReport($event, $request);
            }
        }
    }

    private function userReport($event, $request)
    {
        dd($request->all());
    }

    private function departmentReport($event, $request)
    {
        dd($request->all());

    }

    private function dayReport($event, $request)
    {
        dd($request->all());

    }

    private function hoursVolunteeredReport($event, $request)
    {
        dd($request->all());

    }

    private function shiftsFilledReport($event, $request)
    {
        dd($request->all());

    }
}
