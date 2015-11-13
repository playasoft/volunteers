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

        <img class="pull-right" src="/img/upload/{{ $event->image }}">

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
            <p>{{ $event->description }}</p>
        @endif

        @can('create-department')
            <a href="/event/{{ $event->id }}/department" class="btn btn-primary">Create Department</a>
        @endcan
    </section>
@endsection
