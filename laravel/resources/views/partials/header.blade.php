<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">VolunteerDB</a>
        </div>

        <div class="collapse navbar-collapse">
            <!-- 
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Link</a></li>
                <li><a href="#">Link</a></li>
            </ul>
            -->

            <ul class="nav navbar-nav navbar-right">
                @if(Session::has('user'))
                    <li><a href="/logout">Logout</a></li>
                @else
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
