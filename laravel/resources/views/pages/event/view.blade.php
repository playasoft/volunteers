@extends('app')

@section('content')
    <section class="event" data-id="{{ $event->id }}">
        <div class="pull-right">
            @can('create-event')
                {{-- <a href="/event/{{ $event->id }}/clone" class="btn btn-primary">Clone Event</a> --}}
            @endcan

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
            <img class="pull-right" src="/files/event/{{ $event->image }}">
        @endif
        
        <div>
            <label>Start Date</label>
            {{ $event->start_date }}
        </div>

        <div>
            <label>End Date</label>
            {{ $event->end_date }}
        </div>
        
        @if($event->description)
            <label>Description</label>
            <p>{!! nl2br(e($event->description)) !!}</p>
        @endif

        @can('create-department')
            <a href="/event/{{ $event->id }}/departments" class="btn btn-primary">View All Departments</a>
        @endcan

        @can('create-shift')
            <a href="/event/{{ $event->id }}/shifts" class="btn btn-primary">View All Shifts</a>
        @endcan

        @can('create-schedule')
            <a href="/event/{{ $event->id }}/schedule/create" class="btn btn-primary">Add a Shift to the Schedule</a>
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

                        <div class="shift-wrap">
                            @include('partials/event/timegrid')

                            <div class="department-wrap">
                                @foreach($event->departments as $department)
                                    <?php

                                    if($department->slots->where('start_date', $day->date->format('Y-m-d'))->isEmpty())
                                        continue;

                                    ?>

                                    <div class="department">
                                        <div class="title">
                                            @can('edit-department')
                                                <a href="/department/{{ $department->id }}/edit">{{ $department->name }}</a><br>
                                            @else
                                                <b>{{ $department->name }}</b><br>
                                            @endcan
                                        </div>
                                        
                                        <ul class="shifts">
                                            @foreach($department->schedule as $schedule)
                                                <?php

                                                if($schedule->slots->where('start_date', $day->date->format('Y-m-d'))->isEmpty())
                                                    continue;

                                                ?>

                                                <li class="shift row" data-rows="{{ $schedule->volunteers }}">
                                                    <div class="title col-sm-2">
                                                        @can('edit-schedule')
                                                            <a href="/schedule/{{ $schedule->id }}/edit">{{ $schedule->data->name }}</a>
                                                        @else
                                                            <b>{{ $schedule->data->name }}</b>
                                                        @endcan
                                                    </div>

                                                    <div class="slots col-sm-10">
                                                        @foreach($schedule->slots->where('start_date', $day->date->format('Y-m-d')) as $slot)
                                                            @include('partials/event/slot')
                                                        @endforeach
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div> <!-- / .department-wrap -->
                        </div> <!-- / .shift-wrap -->
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
