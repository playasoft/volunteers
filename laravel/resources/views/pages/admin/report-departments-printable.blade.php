<?php

use Carbon\Carbon;

use App\Helpers;

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

    .icon {
        text-align: center;
        font-size: 2em;
        padding: 0;
    }

    .grey {
        background-color: #ccc;
    }
</style>

@foreach($departments as $department)
    <h1>{{ $department->name }}</h1>

    @foreach($department->schedule()->orderBy('duration', 'desc')->orderBy('start_date')->groupBy('shift_id')->get() as $schedule)
        <?php

        $schedule_ids = $department->schedule()->where('shift_id', $schedule->shift->id)->get()->pluck('id');
        $previousDay = false;
        $background = 1;

        ?>
        <table>
            <thead>
                <tr>
                    <th>{{ $schedule->shift->name }}</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Username</th>
                    <th>Real Name</th>
                    <th>Playa Name</th>
                    <th>Flake?</th>
                    <th>Awesome?</th>
                </tr>
            </thead>

            <tbody>
                @foreach($department->slots()->whereIn('schedule_id', $schedule_ids)->orderBy('start_date')->orderBy('start_time')->get() as $slot)
                    <?php

                    $date = new Carbon($slot->start_date);
                    $day = $date->formatLocalized('%A');
                    $start = strtotime($slot->start_time);
                    $end = strtotime($slot->end_time);

                    if($day != $previousDay)
                    {
                        $background++;
                    }

                    $previousDay = $day;

                    ?>
                    <tr class="{{ $background % 2 ? 'grey' : ''}}">
                        <td>&nbsp;</td>
                        <td>{{ $slot->start_date }}</td>
                        <td>{{ $day }}</td>
                        <td>{{ $slot->start_time }} ({{ date("h:i a", $start) }})</td>
                        <td>{{ $slot->end_time }} ({{ date("h:i a", $end) }})</td>
                        <td>
                            @if($slot->user()->exists())
                                <b>{{ $slot->user->name }}</b>
                            @else
                                OPEN
                            @endif
                        </td>
                        <td><b>{{ $slot->user->data->full_name or '' }}</b></td>
                        <td><b>{{ Helpers::displayName($slot->user) }}</b></td>
                        <td class="icon">◯</td>
                        <td class="icon">◯</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>
            <i>Instructions: Fill in the circles to mark who flaked and who did an awesome job. Be sure to fill the circle in completely, so it looks like this: ⬤</i>
        </p>
        <hr>
        <br><br>
    @endforeach
@endforeach
