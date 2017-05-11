@extends('app')

@section('content')
    <h1>Editing Event: {{ $event->name }}</h1>
    <hr>
    
    {!! Form::open(['files' => true]) !!}
        @include('partials/form/text', ['name' => 'name', 'label' => 'Event Name', 'placeholder' => "What's it called?", 'value' => $event->name])
        @include('partials/form/textarea', ['name' => 'description', 'label' => 'Description', 'placeholder' => 'Tell me as much as you want', 'value' => $event->description])
        @include('partials/form/file', ['name' => 'image', 'label' => 'Promotional Image / Logo', 'value' => $event->image])
        @include('partials/form/date', ['name' => 'start_date', 'label' => 'Start Date', 'value' => $event->start_date])
        @include('partials/form/date', ['name' => 'end_date', 'label' => 'End Date', 'value' => $event->end_date])
        @include('partials/form/checkbox',
        [
            'name' => 'featured',
            'label' => 'Is this a featured event?',
            'options' => ['yes' => 'Yes'],
            'selected' => $event->featured ? ['yes'] : []
        ])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/event/{{ $event->id }}" class="btn btn-primary">Cancel</a>

    {!! Form::close() !!}
@endsection
