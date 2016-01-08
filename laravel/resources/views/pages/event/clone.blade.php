@extends('app')

@section('content')
    <h1>Cloning Event: {{ $event->name }}</h1>
    <hr>
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to clone this event? All information for this event including departments and shifts will be copied to new dates.
        </p>

        @include('partials/form/date', ['name' => 'start_date', 'label' => 'New Start Date'])
        
        <button type="submit" class="btn btn-primary">Clone Event</button>
        <a href="/event/{{ $event->id }}" class="btn btn-danger">Cancel</a>
    {!! Form::close() !!}
@endsection
