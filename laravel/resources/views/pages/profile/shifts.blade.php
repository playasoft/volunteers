<?php use Carbon\Carbon; ?>

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
                        <td>{{ $slot->schedule->shift->name }}</td>
                        <td>{{ Carbon::parse($slot->start_date)->formatLocalized('%A') }} ({{ $slot->start_date }})</td>
                        <td>{{ Carbon::parse($slot->start_time)->format('h:i a') }} ({{ $slot->start_time }})</td>
                        <td>{{ Carbon::parse($slot->end_time)->format('h:i a') }} ({{ $slot->end_time }})</td>
                        <td>
                            <a href="/slot/{{ $slot->id }}/view" class="btn btn-primary">View Details</a>
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
                    @if($slot->event)
                        <tr>
                            <td><a href="/event/{{ $slot->event->id }}">{{ $slot->event->name }}</a></td>
                            <td>{{ $slot->department->name }}</td>
                            <td>{{ $slot->schedule->shift->name }}</td>
                            <td>{{ Carbon::parse($slot->start_date)->formatLocalized('%A') }} ({{ $slot->start_date }})</td>
                            <td>{{ Carbon::parse($slot->start_time)->format('h:i a') }} ({{ $slot->start_time }})</td>
                            <td>{{ Carbon::parse($slot->end_time)->format('h:i a') }} ({{ $slot->end_time }})</td>
                            <td><a href="/slot/{{ $slot->id }}/view" class="btn btn-primary">View Details</a></td>
                        </tr>
                    @endif
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
