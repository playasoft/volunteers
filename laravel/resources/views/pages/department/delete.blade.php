@extends('app')

@section('content')
    <h1>Delete Department: {{ $department->name }}</h1>
    <hr>
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to delete this department? All information for this department including shifts will be removed.
        </p>
        
        <button type="submit" class="btn btn-danger">Delete Department</button>
        <a href="/event/{{ $department->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
