@extends('app')

@section('content')

    <h1>Editing schedule for: {{ $schedule->department->name }}</h1>
    <hr>



    {!! Form::open() !!}

        <div class="general-alert alert alert-danger" role="alert">
            <b>Are you sure?</b>

            <p>
                The changes you've made to this shift could potentially erase user data.
                If users have already started signing up for shifts, changing the duration, dates, times, or number of volunteers may result in users being removed from shifts they've signed up for.
            </p>
        </div>

        <input type="hidden" name="preserved-data" value="{{ base64_encode(serialize($input)) }}">

        <button type="submit" class="btn btn-success">Yes, Save Changes</button>
        <a href="/event/{{ $schedule->event->id }}" class="btn btn-primary">No, Cancel</a>
        
    {!! Form::close() !!}
@endsection
