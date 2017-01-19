<?php

use Carbon\Carbon;

?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2em;
    }

    table, th, td {
        border: 1px solid black;
    }

    thead tr {
        background-color: #434343;
        color: #fff;
    }

    th, td {
        padding: 0.5em 1em;
    }
</style>

@foreach($departments as $department)
    <h1>{{ $department->name }}</h1>

    @foreach($department->shifts()->orderBy('duration', 'desc')->orderBy('start_date')->groupBy('name')->get() as $shift)
        <?php

        $shift_ids = $department->shifts()->where('name', $shift->name)->get()->pluck('id');

        ?>
        <table>
            <thead>
                <tr>
                    <th>{{ $shift->name }}</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Username</th>
                    <th>Real Name</th>
                </tr>
            </thead>

            <tbody>
                @foreach($department->slots()->whereIn('shift_id', $shift_ids)->orderBy('start_date')->orderBy('start_time')->get() as $slot)
                    <?php

                    $date = new Carbon($slot->start_date);
                    $day = $date->formatLocalized('%A');

                    ?>
                    <tr>
                        <td>&nbsp;</td>
                        <td>{{ $slot->start_date }}</td>
                        <td>{{ $day }}</td>
                        <td>{{ $slot->start_time }}</td>
                        <td>{{ $slot->end_time }}</td>
                        <td>{{ $slot->user->name or 'OPEN' }}</td>
                        <td>{{ $slot->user->data->real_name or '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endforeach
