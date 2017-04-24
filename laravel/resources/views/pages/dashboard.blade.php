@extends('app')

@section('content')
    <h1>
        Your Dashboard

        <div class="pull-right" style="font-size:0.4em; margin-top: 1.4em;">
            User Permissions:

            <b>{{ implode(", ", Auth::user()->getRoleNames(['format' => 'ucwords'])) }}</b>
        </div>
    </h1>
    <hr>

    <a href="/profile" class="btn btn-primary">View Your Profile</a>

    @can('create-event')
        <a href="/event" class="btn btn-primary">Create an Event</a>
    @endcan

    @if(count($present))
        <h2>Ongoing Events</h2>
        <hr>

        @foreach($present as $event)
            <p>
                @if($event->featured)
                    <span class="burn glyphicon glyphicon-fire"></span>
                @endif
                
                <b><a href='/event/{{ $event->id }}'>{{ $event->name }}</a></b>
                <i>from {{ $event->start_date }} until {{ $event->end_date }}</i>
            </p>
        @endforeach

        <br>
    @endif

    @if(count($future))
        <h2>Upcoming Events</h2>
        <hr>

        @foreach($future as $event)
            <p>
                @if($event->featured)
                    <span class="burn glyphicon glyphicon-fire"></span>
                @endif

                <b><a href='/event/{{ $event->id }}'>{{ $event->name }}</a></b>
                <i>from {{ $event->start_date }} until {{ $event->end_date }}</i>
            </p>
        @endforeach

        <br>
    @endif

    @if(count($past))
        <h2>Past Events</h2>
        <hr>

        @foreach($past as $event)
            <p>
                <b><a href='/event/{{ $event->id }}'>{{ $event->name }}</a></b>
                <i>from {{ $event->start_date }} until {{ $event->end_date }}</i>
            </p>
        @endforeach

        <br>
    @endif
@endsection
