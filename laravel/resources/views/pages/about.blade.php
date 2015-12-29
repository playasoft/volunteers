@extends('app')

@section('content')
    <h1>About the Volunteer Database</h1>
    <hr>

    <div class="image-caption pull-right">
        <a href="/img/apogaea-volunteer-db.jpg"><img src="/img/apogaea-volunteer-db.jpg"></a>
        <p>The original Apogaea volunteer database</p>
    </div>

    <p>
        This website is a scheduling system where users can sign up for volunteer shifts at festivals.
        Administrators can create events with custom departments and shifts at any time of day.
        It was inspired by the volunteer databases of Apogaea and Elsewhence, but the code was written from scratch.
    </p>

    <h2>Features</h2>

    <h3>Mobile friendly</h3>

    <p>
        This website was developed using the mobile first methodology; all parts of the site are designed to work on phones, tablets, and desktop computers.
    </p>

    <h3>User roles</h3>

    <p>
        Every user has specific roles that allow them to take certain shifts.
        This allows administrators to create medical or fire fighting shifts that only allow approved users.  
    </p>

    <h3>User uploads</h3>

    <p>
        Users can upload files into their profile to be approved by administrators.
        This allows users to upload their EMT or fire fighting certification documents.
    </p>

    <h3>History from previous events</h3>

    <p>
        This website supports creating multiple events, allowing use by many festivals, or allowing a festival to keep a record of who volunteered in the past.
    </p>

    <h3>Customizable shifts</h3>

    <p>
        Event shifts can start and end at any time of day and can be any duration.
        Instead of being locked into a rigid table, this lets administrators create longer shifts for leads and shorter shifts for miscellaneous jobs.
    </p>

    <h3>Websockets</h3>

    <p>
        Portions of this website use websockets, a modern web technology that allows real-time communication between the server and users.
        When someone signs up for a shift, all other users on the event page will see the changes immediately without needing to refresh.
    </p>

    <hr>
    
    <h2>Plans for the Future</h2>

    <p>
        <b>Beta testing is encouraged!</b>
        If you find any problems with the site, please <a href="https://github.com/itsrachelfish/laravel-voldb/issues">open an issue on GitHub</a> or <a href="https://github.com/itsrachelfish/laravel-voldb/pulls">submit a pull request</a> if you know how to program.
        Being built on modern frameworks like Laravel and Bootstrap means it's easy for anyone to add new features.
        There are already several open issues on GitHub detailing the requirements from the unfinished 2014 - 2015 Apogaea volunteer database.
    </p>

    <p>
        Obviously, this website is a work in progress.
        The design needs work and there are several features that should be fleshed out, but overall the system is complete enough for real-world use.
        If there is enough interest from the community, or the project is approved for use by a festival, the project could be moved on GitHub into the control of an organization instead of a single user account. 
    </p>

    <p>
        Moving forward, it will be important to get feedback from people who have volunteered at regionals in the past.
        If you have hands on experience managing a festival and would like to suggest features to help you do your job better, feel free to <a href="mailto:rachel@wetfish.net">send Rachel an email</a>. 
    </p>

    <hr>

    <h2>History</h2>

    <p>
        Back between late 2014 and early 2015, volunteers from Apogaea were working on making updates to their existing volunteer database.
        <a href="https://github.com/itsrachelfish">Rachel Fish</a> is friends with a couple of the developers and was present at a weekend-long hackathon where lots of ideas were shared and code was written aplenty.
        Unfortunately, when Apogaea was postponed in 2015, development of their volunteer database was postponed as well.
    </p>

    <p>
        This project was started in November of 2015 as an experiment in learning the <a href="http://laravel.com/">Laravel framework</a>.
        Over time, what started as a learning exercise turned into a fully featured system that might actually be useful to someone.
        Rachel's previous experience with the old Apogaea database gave her insight into some of the problems the team was facing and inspired her to try something new.
    </p>

    <p>
        Writing in the third person is weird.
    </p>
@endsection
