@extends('app')

@section('content')
    <h1>Welcome to the {{ ucfirst(Auth::user()->role) }} Dashboard!</h1>

    @can('create-events')
        <a href="/events" class="btn btn-primary">Create an Event</a>
    @endcan

    <h2>[List of upcoming events]</h2>

    <hr>

    <h2>[List of past events]</h2>

    @can('create-events')
        <a href="/events" class="btn btn-primary">Create an Event</a>
    @endcan
@endsection
