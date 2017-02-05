@extends('app')

@section('content')
    <h1>Delete Shift: {{ $schedule->data->name }}</h1>
    <hr>
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to delete this schedule? All information including users who have signed up for this schedule will be removed.
        </p>
        
        <button type="submit" class="btn btn-danger">Delete Shift</button>
        <a href="/event/{{ $schedule->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
