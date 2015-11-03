@extends('app')

@section('content')
    <h1>This is the dashboard!</h1>

    <p>You are a [type of user]</p>

    <a href="/events" class="btn btn-primary">Create an Event</a>

    <h2>[List of upcoming events]</h2>

    <hr>

    <h2>[List of past events]</h2>

    <a href="/events" class="btn btn-primary">Create an Event</a>
@endsection
