@extends('app')

@section('content')
    <h1>All Departments for: {{ $event->name }}</h1>
    <hr>

    <a href="/event/{{ $event->id }}" class="btn btn-primary">Back to Event</a>

    @can('create-department')
        <a href="/event/{{ $event->id }}/department/create" class="btn btn-primary">Create New Department</a>
    @endcan

    <hr>

    @foreach($event->departments->sortBy('name') as $department)
        <div>
            <h2>{{ $department->name}}</h2>

            <p>
                {{ $department->description }}
            </p>

            @can('edit-department')
                <a href="/department/{{ $department->id }}/edit" class="btn btn-success">Edit</a>
            @endcan
        </div>

        <hr>
    @endforeach
@endsection
