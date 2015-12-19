@extends('app')

@section('content')
    <div class="jumbotron">
        <h1>Welcome!</h1>
        <p>
            This is <b>Laravel-Voldb</b> a volunteer database built using the Laravel framework.
            This project is based on the online volunteer systems used by festivals like Apogaea and Elsewhence.
        </p>

        <p>
            There are probably lots of other things that have similar systems, but I've never used them. :)
        </p>

        <hr>
        
        <p>
            <a class="btn btn-primary btn-lg" href="/about" role="button">Learn More</a>
            <a class="btn btn-success btn-lg" href="/register" role="button">Register an Account</a>
        </p>
    </div>
@endsection
