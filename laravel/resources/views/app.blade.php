<!DOCTYPE html>
<html>
    <head>
        <title>Volunteer Database</title>

        <!-- Bootstrap styles -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-theme.min.css">

        <!-- Custom styles -->
        <link rel="stylesheet" href="/css/main.css">

        <!-- Custom scripts -->
        <script src="/js/bundle.js"></script>
    </head>
    <body>
        @include('partials/header')

        <section class="content container-fluid">
            @if(Session::has('success'))
                <div class="general-alert alert alert-success" role="alert">
                    <b>Success!</b> {{ Session::get('success') }}
                </div>
            @endif

            @if(Session::has('error'))
                <div class="general-alert alert alert-danger" role="alert">
                    <b>Error!</b> {{ Session::get('error') }}
                </div>
            @endif

            @yield('content')
        </section>

        @include('partials/footer')

        <!-- Media query elements for js -->
        <div class="mobile hidden-md hidden-lg"></div>
        <div class="desktop hidden-xs hidden-sm"></div>
    </body>
</html>
