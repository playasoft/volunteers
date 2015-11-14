@extends('app')

@section('content')
    <h1>Delete Event: {{ $event->name }}</h1>
    <hr>
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to delete this event? All information for this event including departments and shifts will be removed.
        </p>
        
        <button type="submit" class="btn btn-danger">Delete Event</button>
        <a href="/event/{{ $event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
