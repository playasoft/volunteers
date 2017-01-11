<?php

$eventList = [0 => '----'];

foreach($events as $event)
{
    $eventList[$event->id] = $event->name;
}

?>

@extends('app')

@section('content')
    <h1>CSV Report Generator</h1>
    <hr>

    @include('partials/form/select', ['name' => 'event', 'label' => 'Event', 'options' => $eventList])

    @include('partials/form/select',
    [
        'name' => 'type',
        'label' => 'Type of report',
        'class' => 'report-type',
        'options' =>
        [
            '0' => '----',
            'user' => "User Reports",
            'department' => "Department Reports",
            'day' => "Reports by Day",
            'misc' => "Miscellaneous Reports",
        ]
    ])

    <div class="report-options hidden" data-type="user">
        @include('partials/form/select',
        [
            'name' => 'options',
            'label' => 'Report options',
            'class' => 'user-options',
            'options' =>
            [
                '0' => '----',
                'all' => "Use data from all users",
                'specific' => "Search for specific users",
            ]
        ])

        <div class="row">
            <div class="col-md-11">
                @include('partials/form/text',
                [
                    'name' => 'user-search',
                    'label' => 'Search for a user',
                    'class' => 'user-search',
                    'placeholder' => 'rachel@apogaea.com',
                    'help' => 'You can search by user ID, name, or email'
                ])
            </div>

            <div class="col-md-1 search">
                <button class="btn btn-success">Search</button>
            </div>
        </div>

        <div class="loading">
            Loading user data...

            <div class="spinner"></div>
        </div>

        <div class="user-results">
            <h3>Search results</h3>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Real Name</th>
                        <th>Email</th>
                        <th>Include in report?</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><b>1</b></td>
                        <td><a href="/user/1">username</a></td>
                        <td>Example User</td>
                        <td>example@user.com</td>
                        <td>
                            <input type="checkbox" name="user-report[]" value="1">
                        </td>
                    </tr>

                    <tr>
                        <td><b>2</b></td>
                        <td><a href="/user/2">user2</a></td>
                        <td>Another User</td>
                        <td>another@user.com</td>
                        <td>
                            <input type="checkbox" name="user-report[]" value="2">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="report-options hidden" data-type="department">
        @include('partials/form/select',
        [
            'name' => 'options',
            'label' => 'Report options',
            'class' => 'department-options',
            'options' =>
            [
                '0' => '----',
                'all' => "Use data from all departments",
                'specific' => "Select specific departments",
            ]
        ])

        <div class="loading">
            Loading department data...

            <div class="spinner"></div>
        </div>

        <div class="departments">
            <h3>Departments</h3>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Department</th>
                        <th>Include in report?</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><b>1</b></td>
                        <td><a href="/department/1/edit">BAMF</a></td>
                        <td>
                            <input type="checkbox" name="department-report[]" value="1">
                        </td>
                    </tr>

                    <tr>
                        <td><b>2</b></td>
                        <td><a href="/department/2/edit">DPW</a></td>
                        <td>
                            <input type="checkbox" name="department-report[]" value="2">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="report-options hidden" data-type="day">
        @include('partials/form/select',
        [
            'name' => 'options',
            'label' => 'Report options',
            'class' => 'day-options',
            'options' =>
            [
                '0' => '----',
                'all' => "Use data from all days",
                'specific' => "Select specific days",
            ]
        ])

        <div class="loading">
            Loading event data...

            <div class="spinner"></div>
        </div>

        <div class="days">
            <h3>Event Days</h3>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Include in report?</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><b>1/11/2017</b></td>
                        <td>Wednesday</td>
                        <td>
                            <input type="checkbox" name="day-report[]" value="2017-1-11">
                        </td>
                    </tr>

                    <tr>
                        <td><b>1/12/2017</b></td>
                        <td>Thursday</td>
                        <td>
                            <input type="checkbox" name="day-report[]" value="2017-1-12">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="report-options hidden" data-type="misc">
        @include('partials/form/select',
        [
            'name' => 'misc',
            'label' => 'Miscellaneous reports',
            'class' => 'misc-options',
            'options' =>
            [
                '0' => '----',
                'hours-volunteered' => "Total hours volunteered by user",
                'shifts-filled' => "Total shifts filled by department",
            ]
        ])
    </div>

    <button class="btn btn-primary">Generate Report</button>
@endsection
