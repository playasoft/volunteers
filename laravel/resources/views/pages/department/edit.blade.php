@extends('app')

@section('content')
    <div class="header-buttons pull-right">
        @can('create-shift')
            <a href="/event/{{ $department->event->id }}/shift/create" class="btn btn-primary">Create Shift</a>
        @endcan

        @can('delete-department')
            <a href="/department/{{ $department->id }}/delete" class="btn btn-danger">Delete Department</a>
        @endcan
    </div>

    <h1>Editing Department for: {{ $department->event->name }}</h1>
    <hr>

    {!! Form::open() !!}
        <input type="hidden" name="event_id" value="{{ $department->event->id }}">

        @include('partials/form/text', ['name' => 'name', 'label' => 'Department Name', 'placeholder' => "General department name", 'value' => $department->name])
        @include('partials/form/textarea', ['name' => 'description', 'label' => 'Description', 'placeholder' => 'A brief description of this department', 'value' => $department->description])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/event/{{ $department->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
