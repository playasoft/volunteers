@extends('app')

@section('content')
    <h1>
        Your Dashboard
        <div class="pull-right" style="font-size:0.4em; margin-top: 1.4em;">User Level: <b>{{ ucfirst(Auth::user()->role) }}</b></div>
    </h1>
    <hr>

    @can('create-event')
        <a href="/event" class="btn btn-primary">Create an Event</a>
    @endcan

    <h2>[List of upcoming events]</h2>

    <hr>

    <h2>[List of past events]</h2>

    @can('create-event')
        <a href="/event" class="btn btn-primary">Create an Event</a>
    @endcan
@endsection
