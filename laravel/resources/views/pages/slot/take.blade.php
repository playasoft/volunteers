@extends('app')

@section('content')

    <h1>Volunteer for: {{ $slot->shift->name }}</h1>
    <hr>

    {!! Form::open() !!}
        <div>Hi there!</div>
        
        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/event/{{ $slot->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
