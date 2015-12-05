@extends('app')

@section('content')
    <h1>Don't volunteer for: {{ $slot->department->name }} - {{ $slot->shift->name }}</h1>
    <hr>

    <div>
        <label>Start Date</label>
        {{ $slot->start_date }}
    </div>

    <div>
        <label>Start Time</label>
        {{ $slot->start_time }}
    </div>

    <div>
        <label>End Time</label>
        {{ $slot->end_time }}
    </div>

    @if($slot->department->description)
        <label>About {{ $slot->department->name }}</label>
        <p>{!! nl2br(e($slot->department->description)) !!}</p>
    @endif
    
    {!! Form::open() !!}
        <p>
            Are you sure you want to cancel volunteering for this shift?
            By canceling, your slot will be available for other people to take.
        </p>
        
        <button type="submit" class="btn btn-danger">Release Shift</button>
        <a href="/event/{{ $slot->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
