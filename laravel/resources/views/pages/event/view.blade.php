@extends('app')

@section('content')
    <section class="event">
        <div class="pull-right">
            @can('edit-event')
                <a href="/event/{{ $event->id }}/edit" class="btn btn-primary">Edit Event</a>
            @endcan

            @can('delete-event')
                <a href="/event/{{ $event->id }}/delete" class="btn btn-danger">Delete Event</a>
            @endcan
        </div>
        
        <h1>Viewing Event: {{ $event->name }}</h1>
        <hr>

        @if($event->image)
            <img class="pull-right" src="/img/upload/{{ $event->image }}">
        @endif
        <div>
            <label>Start Date</label>
            {{ $event->start_date->format('Y-m-d') }}
        </div>

        <div>
            <label>End Date</label>
            {{ $event->end_date->format('Y-m-d') }}
        </div>
        
        @if($event->description)
            <label>Description</label>
            <p>{!! nl2br(e($event->description)) !!}</p>
        @endif

        @can('create-department')
            <a href="/event/{{ $event->id }}/department" class="btn btn-primary">Create Department</a>
        @endcan

        <div class="clearfix"></div>

        <h2>Sign up for a shift!</h2>
        <hr>

        <div class="days">
            @foreach($event->days() as $day)
                <div class="day">
                    <div class="heading">
                        <h3>{{ $day->name }}</h3>
                        <i>{{ $day->date->format('Y-m-d') }}</i>
                    </div>

                    <div class="shifts">
                        @foreach($event->departments as $department)
                            @can('edit-department')
                                <a href="/department/{{ $department->id }}/edit">{{ $department->name }}</a><br>
                            @else
                                <b>{{ $department->name }}</b><br>
                            @endcan
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
