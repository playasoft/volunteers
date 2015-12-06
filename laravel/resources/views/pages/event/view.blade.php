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
                            <div class="row hidden-xs hidden-sm">
                                <div class="col-sm-2"><!-- Spacing --></div>
                                <div class="grid-wrap col-sm-10">
                                    @include('partials/timegrid')
                                </div>
                            </div>
                            
                            @foreach($event->departments as $department)
                                <?php

                                if($department->slots->where('start_date', $day->date->format('Y-m-d'))->isEmpty())
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

                                        <li class="row">
                                            <div class="col-sm-2">
                                                @can('edit-shift')
                                                    <a href="/shift/{{ $shift->id }}/edit">{{ $shift->name }}</a>
                                                @else
                                                    <b>{{ $shift->name }}</b>
                                                @endcan
                                            </div>

                                            <div class="col-sm-10">                                                
                                                @foreach($shift->slots->where('start_date', $day->date->format('Y-m-d')) as $slot)
                                                    @if(is_null($slot->user))
                                                        <a href="/slot/{{ $slot->id }}/take" class="slot"></a>
                                                    @else
                                                        @if($slot->user->id === Auth::user()->id)
                                                            <a href="/slot/{{ $slot->id }}/release" class="slot taken">{{ $slot->user->name }}</a>
                                                        @else
                                                            <a class="slot taken col-sm-1">{{ $slot->user->name }}</a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
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
