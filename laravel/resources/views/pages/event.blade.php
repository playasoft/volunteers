@extends('app')

@section('content')
    <h1>Create an Event</h1>
    <hr>
    
    {!! Form::open() !!}
        @include('partials/form/input', ['name' => 'name', 'label' => 'Event Name', 'placeholder' => "What's it called?"])
        @include('partials/form/textarea', ['name' => 'description', 'label' => 'Description', 'placeholder' => 'Tell me as much as you want'])
        @include('partials/form/date', ['name' => 'start_date', 'label' => 'Start Date'])
        @include('partials/form/date', ['name' => 'end_date', 'label' => 'End Date'])

        <button type="submit" class="btn btn-primary">Submit</button>
    {!! Form::close() !!}
@endsection
