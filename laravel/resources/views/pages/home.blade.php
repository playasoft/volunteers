@extends('app')

@section('content')
    <div class="jumbotron">
        <h1>Apogaea Volunteer Database</h1>
        <p>
            This is the official volunteer database for <b>Apogaea</b>, Colorado's regional Burning Man event.
        </p>

        <p>
            Registration is <b>currently open</b> for all Apogaea participants.
            <b>It is so fun to volunteer!</b>
            And seriously, it is the cool thing to do.
            Coordinate with your camp and friends to be a group during a shift. Costume themes highly encouraged!
        </p>

        <p>
            If you have a ticket for Apogaea you can sign up now and register for some shifts.
            If you don't have a ticket yet you can still get a guaranteed ticket* by volunteering for certain shifts.
            <a href="http://apogaea.com/get-involved/volunteer">Contact department leads for more info!</a>
        </p>


        <div class="fine-print">
            * Although there are volunteer tickets available, you will need to go through the specific department in order to make arrangements for qualifying for one of those tickets.
            Signing up here will not qualify you for a ticket.
            If a shift is password or role protected (i.e. Medical), contact the department lead to get the passcode or give you permissions for that shift.
        </div>

        <hr>
        
        <p>
            <a class="btn btn-primary btn-lg" href="/about" role="button">Learn More</a>
            <a class="btn btn-success btn-lg" href="/register" role="button">Register an Account</a>
        </p>
    </div>
@endsection
