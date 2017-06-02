<?php

$eventList = [0 => '----'];

foreach($events as $event)
{
    $eventList[$event->id] = $event->name;
}

?>

@extends('app')

@section('content')
    <div class="report-generator">
        <input type="hidden" class="csrf-token" name="_token" value="{{ csrf_token() }}">

        {!! Form::open(['url' => 'report/generate']) !!}
            <h1>Report Generator</h1>
            <hr>

            @include('partials/form/select',
            [
                'name' => 'event',
                'label' => 'Event',
                'class' => 'report-event',
                'options' => $eventList
            ])

            <div class="report-types hidden">
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
                        'misc' => "Miscellaneous Reports",
                    ]
                ])
            </div>

            <div class="report-options hidden" data-type="user">
                @include('partials/form/select',
                [
                    'name' => 'user-options',
                    'label' => 'Report options',
                    'class' => 'user-options',
                    'options' =>
                    [
                        '0' => '----',
                        'all' => "Use data from all users",
                        'specific' => "Search for a specific user",
                    ]
                ])

                <div class="row user-search hidden">
                    <div class="col-md-11">
                        @include('partials/form/text',
                        [
                            'name' => 'user-search',
                            'label' => 'Search for a user',
                            'placeholder' => 'rachel@apogaea.com',
                            'help' => 'You can search by user ID, username, or email'
                        ])
                    </div>

                    <div class="col-md-1 search">
                        <button class="btn btn-success">Search</button>
                    </div>
                </div>

                <div class="user-wrap">
                    <div class="loading hidden">
                        Loading user data...

                        <div class="spinner"></div>
                    </div>

                    <div class="users hidden">
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

                                <tr class="template hidden">
                                    <td><b>{user_id}</b></td>
                                    <td><a href="/user/{user_id}">{username}</a></td>
                                    <td>{full_name}</td>
                                    <td>{email}</td>
                                    <td>
                                        <input type="checkbox" name="user-report[]" value="{user_id}">
                                    </td>
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
            </div>

            <div class="report-options hidden" data-type="department">
                @include('partials/form/select',
                [
                    'name' => 'department-options',
                    'label' => 'Report options',
                    'class' => 'department-options',
                    'options' =>
                    [
                        '0' => '----',
                        'all' => "Use data from all departments",
                        'specific' => "Select specific departments",
                    ]
                ])

                @include('partials/form/select',
                [
                    'name' => 'department-output',
                    'label' => 'File output',
                    'options' =>
                    [
                        'csv' => "Export raw data as CSV file",
                        'printable' => "Export printable file",
                    ]
                ])

                <div class="departments-wrap">
                    <div class="loading hidden">
                        Loading department data...

                        <div class="spinner"></div>
                    </div>


                    <div class="departments hidden">
                        <h3>Departments</h3>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Department</th>
                                    <th>Include in report?</th>
                                </tr>

                                <tr class="template hidden">
                                    <td><b>{department_id}</b></td>
                                    <td><a href="/department/{department_id}/edit">{department_name}</a></td>
                                    <td>
                                        <input type="checkbox" name="department-report[]" value="{department_id}">
                                    </td>
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
            </div>

            <div class="report-options hidden" data-type="misc">
                @include('partials/form/select',
                [
                    'name' => 'misc-reports',
                    'label' => 'Miscellaneous reports',
                    'class' => 'misc-options',
                    'options' =>
                    [
                        '0' => '----',
                        'hours-volunteered' => "Total hours volunteered by user",
                        'shifts-filled' => "Total shifts filled by department",
                        'popular-camps' => "Most popular camps",
                    ]
                ])
            </div>

            <input class="btn btn-primary" type="submit" value="Generate Report">
        {!! Form::close() !!}
    </div>
@endsection
