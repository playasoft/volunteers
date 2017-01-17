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
                'real_name' => count($user->data) && $user->data->real_name ? $user->data->real_name : '',
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
        if($request->get('user-options') == 'specific')
        {
            $ids = $request->get('user-report');

            if(empty($ids))
            {
                $request->session()->flash('error', 'No user selected. Please use the checkboxes to generate reports for specific users.');
                return redirect()->back();
            }

            // Select users based on specified user IDs
            $users = User::whereIn('id', $ids)->get();
        }
        else
        {
            // Select all users
            $users = User::get();
        }

        // Select all shifts in the selected event
        $shifts = [];

        foreach($event->departments as $department)
        {
            foreach($department->shifts as $shift)
            {
                $shifts[] = $shift->id;
            }
        }

        $columns =
        [
            'user' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'day' => 'Day of the Week',
            'date' => 'Date',
            'department' => 'Department',
            'shift' => 'Shift',
            'start_time' => 'Start Time',
            'end_time' => 'End Time'
        ];

        $data = [];

        foreach($users as $user)
        {
            $name = ['first' => '', 'last' => ''];

            if(count($user->data))
            {
                $name = $this->splitName($user->data->real_name);
            }

            $slots = $user->slots()->whereIn('shift_id', $shifts)->get();

            foreach($slots as $slot)
            {
                $date = new Carbon($slot->start_date);

                $data[] =
                [
                    'user' => $user->name,
                    'first_name' => $name['first'],
                    'last_name' => $name['last'],
                    'day' => $date->formatLocalized('%A'),
                    'date' => $date->format('m/d/Y'),
                    'department' => $slot->department->name,
                    'shift' => $slot->shift->name,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time
                ];
            }
        }

        $this->generateCSV('Volunteer DB User Report - ' . date('Y-m-d H:i:s'), $columns, $data);
    }

    private function departmentReport($event, $request)
    {
        if($request->get('department-options') == 'specific')
        {
            $ids = $request->get('department-report');

            if(empty($ids))
            {
                $request->session()->flash('error', 'No departments selected. Please use the checkboxes to generate reports for specific departments.');
                return redirect()->back();
            }

            // Select deparatments based on specified IDs
            $departments = $event->departments()->whereIn('id', $ids)->get();
        }
        else
        {
            // Select all departments
            $departments = $event->departments;
        }

        $columns =
        [
            'department' => 'Department',
            'shift' => 'Shift',
            'day' => 'Day of the Week',
            'date' => 'Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'user' => 'Username',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone Number'
        ];

        $data = [];

        foreach($departments as $department)
        {
            foreach($department->slots as $slot)
            {
                $date = new Carbon($slot->start_date);

                $row =
                [
                    'department' => $department->name,
                    'shift' => $slot->shift->name,
                    'day' => $date->formatLocalized('%A'),
                    'date' => $date->format('m/d/Y'),
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time
                ];

                if(count($slot->user))
                {
                    $row['user'] = $slot->user->name;
                    $row['email'] = $slot->user->email;

                    if(count($slot->user->data))
                    {
                        $name = $this->splitName($slot->user->data->real_name);
                        $row['first_name'] = $name['first'];
                        $row['last_name'] = $name['last'];
                        $row['phone'] = $slot->user->data->phone;
                    }
                }

                $data[] = $row;
            }
        }

        $this->generateCSV('Volunteer DB Department Report - ' . date('Y-m-d H:i:s'), $columns, $data);
    }

    private function dayReport($event, $request)
    {
        $columns =
        [
            'umm' => 'idk?',
        ];

        $data = [];

/*
        foreach($departments as $department)
        {
            foreach($department->slots as $slot)
            {
                $date = new Carbon($slot->start_date);

                $row =
                [
                    'umm' => 'still dk',
                ];

                if(count($slot->user))
                {
                    $row['user'] = $slot->user->name;
                    $row['email'] = $slot->user->email;

                    if(count($slot->user->data))
                    {
                        $name = $this->splitName($slot->user->data->real_name);
                        $row['first_name'] = $name['first'];
                        $row['last_name'] = $name['last'];
                        $row['phone'] = $slot->user->data->phone;
                    }
                }

                $data[] = $row;
            }
        }
*/

        dd($request->all());

        $this->generateCSV('Volunteer DB Daily Report - ' . date('Y-m-d H:i:s'), $columns, $data);
    }

    private function hoursVolunteeredReport($event, $request)
    {
        // Get all slots for an event
        // Sum all slots together grouped by user?

        // Last name, first name, nickname, number of shifts, total hours

        dd($request->all());

    }

    private function shiftsFilledReport($event, $request)
    {
        // Get all slots for an event
        // Find how many are filled vs empty
        // Group by department
        // Output each by department as well as a total

        // Department, Shifts Filled, Shifts Empty, Percent Filled

        dd($request->all());

    }

    private function generateCSV($filename, $columns, $data)
    {
        $filename = $filename . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        $columnNames = array_values($columns);

        $file = fopen('php://output', 'w');
        fputcsv($file, $columnNames);

        foreach($data as $row)
        {
            $output = [];

            foreach($columns as $column => $columnName)
            {
                if(isset($row[$column]))
                {
                    $output[] = $row[$column];
                }
                else
                {
                    $output[] = '';
                }
            }

            fputcsv($file, $output);
        }

        fclose($file);
    }

    private function splitName($name)
    {
        $name = explode(' ', $name);

        $split = array
        (
            'first' => implode(' ', array_slice($name, 0, -1)),
            'last' => implode(' ', array_slice($name, -1))
        );

        return $split;
    }
}
