<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">VolunteerDB</a>
        </div>

        <div class="collapse navbar-collapse"> 
            @if(Auth::check())
                <ul class="nav navbar-nav">
                    @if(Auth::user()->role == 'admin')
                        <li><a href="/event">New Event</a></li>
                        <li><a href="/profile/list">View Users</a></li>
                    @endif;

                    <li><a href="/profile/events">Your Events</a></li>
                </ul>
            @endif
            
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    <li><a href="/profile">{{ Auth::user()->name }}</a></li>
                    <li><a href="/logout">Logout</a></li>
                @else
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
