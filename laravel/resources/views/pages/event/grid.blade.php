@extends('app')

@section('content')
    <h1>Available Shifts by Time: {{ $event->name }}</h1>
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
@endsection
