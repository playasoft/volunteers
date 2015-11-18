@extends('app')

@section('content')
    <div class="header-buttons pull-right">
        @can('delete-shift')
            <a href="/shift/{{ $shift->id }}/delete" class="btn btn-danger">Delete Shift</a>
        @endcan
    </div>

    <h1>Editing Shift for: {{ $shift->department->event->name }}</h1>
    <hr>

    {!! Form::open() !!}
        <input type="hidden" name="department_id" value="{{ $shift->department->id }}">
    
        @include('partials/form/text', ['name' => 'name', 'label' => 'Shift Name', 'placeholder' => "General department name", 'value' => $department->name])
        @include('partials/form/textarea', ['name' => 'description', 'label' => 'Description', 'placeholder' => 'A brief description of this department', 'value' => $department->description])
        @include('partials/roles', ['roles' => json_decode($department->roles)])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/event/{{ $shift->department->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
