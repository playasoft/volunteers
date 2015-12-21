<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">VolunteerDB</a>
        </div>

        <div class="collapse navbar-collapse"> 
            <ul class="nav navbar-nav">
                <li><a href="/about">About</a></li>
                
                @if(Auth::check())
                        <li><a href="/profile/shifts">Your Shifts</a></li>

                        @if(Auth::user()->role == 'admin')
                            <li><a href="/event">New Event</a></li>
                            <li><a href="/users">Users</a></li>
                            <li><a href="/uploads">Uploads</a></li>
                        @endif
                @endif
            </ul>
            
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    <li class="active"><a href="/profile">{{ Auth::user()->name }}</a></li>
                    <li><a href="/logout">Logout</a></li>
                @else
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
