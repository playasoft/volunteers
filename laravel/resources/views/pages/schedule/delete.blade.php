@extends('app')

@section('content')
    <h1>Delete shift from the schedule: {{ $schedule->shift->name }}</h1>
    <hr>
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to delete this shift from the schedule? All information including users who have signed up for this shift will be removed.
        </p>

        <button type="submit" class="btn btn-danger">Delete from the Schedule</button>
        <a href="/event/{{ $schedule->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
