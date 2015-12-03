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

        @can('create-shift')
            <a href="/event/{{ $event->id }}/shift" class="btn btn-primary">Create Shift</a>
        @endcan

        <div class="clearfix"></div>

        @if($event->departments->count())
            <h2>Available Shifts by Department</h2>
            <hr>

            <div class="days">
                @foreach($event->days() as $day)
                    <div class="day">
                        <div class="heading">
                            <h3>{{ $day->name }}</h3>
                            &mdash; <i>{{ $day->date->format('Y-m-d') }}</i>
                        </div>

                        <div class="shifts">
                            @foreach($event->departments as $department)
                                <?php

                                if($department->shifts->isEmpty())
                                    continue;

                                ?>
                            
                                @can('edit-department')
                                    <a href="/department/{{ $department->id }}/edit">{{ $department->name }}</a><br>
                                @else
                                    <b>{{ $department->name }}</b><br>
                                @endcan

                                <ul>
                                    @foreach($department->shifts as $shift)
                                        <?php

                                        if($shift->slots->where('start_date', $day->date->format('Y-m-d'))->isEmpty())
                                            continue;

                                        ?>

                                        <li>
                                            @can('edit-shift')
                                                <a href="/shift/{{ $shift->id }}/edit">{{ $shift->name }}</a>
                                            @else
                                                <b>{{ $shift->name }}</b>
                                            @endcan

                                            @foreach($shift->slots->where('start_date', $day->date->format('Y-m-d')) as $slot)
                                                [ Slot ]
                                            @endforeach
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            @can('create-department')
                <a href="/event/{{ $event->id }}/department" class="btn btn-primary">Create Department</a>
            @endcan

            @can('create-shift')
                <a href="/event/{{ $event->id }}/shift" class="btn btn-primary">Create Shift</a>
            @endcan
        @endif
    </section>
@endsection
