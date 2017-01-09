@extends('app')

@section('content')
    <h1>CSV Report Generator</h1>
    <hr>

    [Select from list of 10 most recent events]

    [Radio buttons to control type of report]

    <ul>
        <li>
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
        </li>

        <li>
            Reports by Department

            <pre>
                [When selected, these options appear:]
                - All departments
                - Specific department
                    - When selected, a list of departments appears with checkboxes to select each
            </pre>
        </li>

        <li>
            Reports by Day

            <pre>
                [When selected, these options appear:]
                - All days
                - Specific day
                    - When selected, a list of all event days appears with checkboxes to select each
            </pre>
        </li>

        <li>
            Miscellaneous reports

            <pre>
                [When selected, these options appear:]
                - Total hours volunteered by volunteer
                - Total shifts filled by department
            </pre>
        </li>
    </ul>

    <button class="btn btn-primary">Generate Report</button>
@endsection
