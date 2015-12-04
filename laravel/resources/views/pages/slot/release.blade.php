@extends('app')

@section('content')
    <h1>Don't volunteer for: {{ $slot->shift->name }}</h1>
    <hr>
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to delete this slot? All information including users who have signed up for this slot will be removed.
        </p>
        
        <button type="submit" class="btn btn-danger">Delete Shift</button>
        <a href="/event/{{ $slot->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
