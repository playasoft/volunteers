@extends('app')

@section('content')
    <div class="jumbotron">
        <h1>Welcome!</h1>
        <p>
            This is <b>Laravel-Voldb</b> a volunteer database built using the Laravel framework.
            This project is based on the online volunteer systems used by festivals like Apogaea and Elsewhence.
        </p>

        <p>
            We are currently in <b>open beta</b>!
        </p>

        <p>
            Feel free to register and sign up for some shifts.
            All of the jobs here are only being used for testing&mdash;you won't be held responsible for your imaginary duties.
        </p>

        <hr>
        
        <p>
            <a class="btn btn-primary btn-lg" href="/about" role="button">Learn More</a>
            <a class="btn btn-success btn-lg" href="/register" role="button">Register an Account</a>
        </p>
    </div>
@endsection
