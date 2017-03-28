@extends('app')

@section('content')
    <div class="jumbotron">
        <h1>Apogaea Volunteer Database</h1>
        <p>
            This is the official volunteer database for <b>Apogaea</b>, Colorado's regional Burning Man event.
        </p>

        <p>
            Registration is currently open for <b>department leads</b> and members of <b>ignition</b>.
        </p>

        <p>
            If you are an Apogaea participant and want to start signing up for shifts, please check back in a couple weeks once everything is setup.
        </p>

        <hr>
        
        <p>
            <a class="btn btn-primary btn-lg" href="/about" role="button">Learn More</a>
            <a class="btn btn-success btn-lg" href="/register" role="button">Register an Account</a>
        </p>
    </div>
@endsection
