@extends('app')

@section('content')
    <h1>All Shifts for: {{ $event->name }}</h1>
    <hr>

    <a href="/event/{{ $event->id }}" class="btn btn-primary">Back to Event</a>

    @can('create-shift')
        <a href="/event/{{ $event->id }}/shift/create" class="btn btn-primary">Create New Shift</a>
    @endcan

    <hr>

    @foreach($event->departments->sortBy('name') as $department)
        <div>
            <h2>{{ $department->name}}</h2>

            <p>
                @can('edit-department')
                    <a href="/department/{{ $department->id }}/edit" class="btn btn-primary">Edit Department</a>
                @endcan
            </p>

            @foreach($department->shifts as $shift)
                <div>
                    <b>
                        {{ $shift->name }}
                    </b>

                    <p>
                        {{ $shift->description }}
                    </p>

                    <p>
                        @can('edit-shift')
                            <a href="/shift/{{ $shift->id }}/edit" class="btn btn-success">Edit Shift</a>
                        @endcan
                    </p>
                </div>
            @endforeach
        </div>

        <hr>
    @endforeach
@endsection
