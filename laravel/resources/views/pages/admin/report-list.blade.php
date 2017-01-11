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
            'user' => "Reports by User",
            'department' => "Reports by Department",
            'day' => "Reports by Day",
            'misc' => "Miscellaneous Reports",
        ]
    ])

    <div class="report-options hidden" data-type="user">
        Reports by User

        <pre>
            [When selected, these options appear:]
            - All users
            - Specific user
                - When selected, a box appears:
                "Search for user by ID, name, or email"
                - User clicks "submit" and results appear
                - In each row of results there is a checkbox to select this user
        </pre>
    </div>

    <div class="report-options hidden" data-type="department">
        Reports by Department

        <pre>
            [When selected, these options appear:]
            - All departments
            - Specific department
                - When selected, a list of departments appears with checkboxes to select each
        </pre>
    </div>

    <div class="report-options hidden" data-type="day">
        Reports by Day

        <pre>
            [When selected, these options appear:]
            - All days
            - Specific day
                - When selected, a list of all event days appears with checkboxes to select each
        </pre>
    </div>

    <div class="report-options hidden" data-type="misc">
        Miscellaneous reports

        <pre>
            [When selected, these options appear:]
            - Total hours volunteered by volunteer
            - Total shifts filled by department
        </pre>
    </div>

    <button class="btn btn-primary">Generate Report</button>
@endsection
