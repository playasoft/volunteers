@extends('app')

@section('content')

    <div class="header-buttons pull-right">
        @can('delete-shift')
            <a href="/shift/{{ $shift->id }}/delete" class="btn btn-danger">Delete Shift</a>
        @endcan
    </div>

    <h1>Editing Shift for: {{ $shift->department->name }}</h1>
    <hr>

    {!! Form::open() !!}
{{ $shift->department->event->name }}
    
        {{ dd($shift->event) }}
    {!! Form::close() !!}
@endsection
