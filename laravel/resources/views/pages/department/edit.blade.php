@extends('app')

@section('content')
    <h1>Editing Department: {{ $department->name }}</h1>
    <hr>

    {!! Form::open() !!}
        @include('partials/form/input', ['name' => 'name', 'label' => 'Department Name', 'placeholder' => "General department name"])
        @include('partials/form/textarea', ['name' => 'description', 'label' => 'Description', 'placeholder' => 'A brief description of this department'])
        @include('partials/roles');

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/department/{{ $department->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
