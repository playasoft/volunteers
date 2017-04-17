@extends('app')

@section('content')

    <h1>Volunteer for: {{ $slot->department->name }} - {{ $slot->schedule->shift->name }}</h1>
    <hr>

    {!! Form::open() !!}
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

        @if($slot->schedule->password)
            <div>
                @include('partials/form/text', ['name' => 'password', 'label' => 'This shift requires a password', 'help' => "This shift has been reserved. You must recieve a password from the department lead in order to take this shift."])
            </div>
        @else
            <div>
                <label>Allowed User Groups</label>
                <ul>
                    @foreach($slot->schedule->getRoles() as $scheduleRole)
                        <li>{{ ucwords($scheduleRole->role->name) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p>
            Are you sure you want to volunteer for this shift?
            By registering, you will be expected to perform the duties of this shift for the entire time listed.
            Please arrive at least 15 minutes ahead of time to be briefed by the previous shift team and answer any questions you have.
        </p>
        
        <button type="submit" class="btn btn-success">Take Shift</button>
        <a href="/event/{{ $slot->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
