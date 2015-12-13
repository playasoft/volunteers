@extends('app')

@section('content')
    <h1>Shifts You've Volunteered For</h1>
    <hr>

    @if($upcoming->count())
        <h2>Upcoming Shifts</h2>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Department</th>
                    <th>Shift</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                @foreach($upcoming as $slot)
                    <tr>
                        <td><a href="/event/{{ $slot->event->id }}">{{ $slot->event->name }}</a></td>
                        <td>{{ $slot->department->name }}</td>
                        <td>{{ $slot->shift->name }}</td>
                        <td>{{ $slot->start_date }}</td>
                        <td>{{ $slot->start_time }}</td>
                        <td>{{ $slot->end_time }}</td>
                        <td>
                            <a href="/slot/{{ $slot->id }}/take" class="btn btn-primary">Description</a>
                            <a href="/slot/{{ $slot->id }}/release" class="btn btn-danger">Cancel</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($past->count())
        <h2>Past Shifts</h2>
    
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Department</th>
                    <th>Shift</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                @foreach($past as $slot)
                    <tr>
                        <td><a href="/event/{{ $slot->event->id }}">{{ $slot->event->name }}</a></td>
                        <td>{{ $slot->department->name }}</td>
                        <td>{{ $slot->shift->name }}</td>
                        <td>{{ $slot->start_date }}</td>
                        <td>{{ $slot->start_time }}</td>
                        <td>{{ $slot->end_time }}</td>
                        <td><a href="/slot/{{ $slot->id }}/take" class="btn btn-primary">Description</a></td>
                    </tr>                
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!$upcoming->count() && !$past->count())
        <div class="general-alert alert alert-danger" role="alert">
            <b>Hey!</b> You haven't signed up for any shifts yet. When you do, they will be listed here.
        </div>
    @endif    
@endsection
