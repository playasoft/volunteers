@extends('app')

@section('content')
    <h1>Create a Shift for: {{ $event->name }}</h1>
    <hr>
    
    {!! Form::open(['url' => '/department']) !!}
        <input type="hidden" name="event_id" value="{{ $event->id }}">

        <pre>// Todo</pre>

        <button type="submit" class="btn btn-primary">Submit</button>
    {!! Form::close() !!}
@endsection
