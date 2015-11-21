@extends('app')

@section('content')
    <h1>Delete Shift: {{ $shift->name }}</h1>
    <hr>
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to delete this shift? All information including users who have signed up for this shift will be removed.
        </p>
        
        <button type="submit" class="btn btn-danger">Delete Shift</button>
        <a href="/event/{{ $shift->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
