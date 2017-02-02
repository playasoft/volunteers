@extends('app')

@section('content')
    <h1>All Departments for: {{ $event->name }}</h1>
    <hr>

    @can('create-department')
        <a href="/event/{{ $event->id }}/department/create" class="btn btn-primary">Create New Department</a>

        <hr>
    @endcan

    @foreach($event->departments as $department)
        <div>
            <h2>{{ $department->name}}</h2>

            <p>
                {{ $department->description }}
            </p>

            @can('edit-department')
                <a href="/department/{{ $department->id }}/edit" class="btn btn-success">Edit</a>
            @endcan

            @can('create-shift')
                <a href="/department/{{ $department->id }}/shifts" class="btn btn-primary">View Shifts</a>
            @endcan
        </div>

        <hr>
    @endforeach
@endsection
