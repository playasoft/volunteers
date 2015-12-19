@extends('app')

@section('content')
    <h1>About the Volunteer Database</h1>
    <hr>

    <div class="image-caption pull-right" style="width:50%" >
        <img style="max-width:100%;" src="/img/apogaea-volunteer-db.jpg">
        <p>The original Apogaea volunteer database</p>
    </div>

    <p>
        This website is a volunteer database where users can sign up for shifts...
    </p>

    <h2>Features</h2>

    <ul>
        <li>Mobile friendly</li>
        <li>User roles</li>
        <li>Uploads</li>
        <li>Supports viewing history from past years</li>
        <li>Shifts can start at any time and be any duration</li>
        <li>Websockets</li>
    </ul>
    
    <h2>Plans for the Future</h2>

    <p>
        There are a bunch of open issues on github detailing the requirements from the unfinished 2014 - 2015 volunteer database.
        I'd be happy to work with anyone from Apogaea to continue development of this application and help determine what features are most important moving foward.
    </p>

    <h2>History</h2>

    <p>
        This project was started by <a href="https://github.com/itsrachelfish">Rachel Fish</a> in the winter of 2015 as an experiment in learning the <a href="http://laravel.com/">Laravel framework</a>.
        Over time, what started as an experiment for work turned into a fully featured system that might actually be useful to someone. 
    </p>

    <p>
        Writing in the third person is weird.
    </p>
@endsection
