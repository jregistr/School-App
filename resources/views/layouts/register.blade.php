<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Set The Title') }}</title>

    <!-- Bootstrap -->

    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="icon" href="/images/graduation-school-hat.png"/>
    <link rel="stylesheet" href="dist/register.bundle.css" />
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div class="register">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/graduation-school-hat.png">
                    {{ config('app.name', 'Set the title') }}
                </a>
            </div>
        </div>
    </nav>

    @yield('content')
</div>

<script src="dist/register.bundle.js"></script>
</body>
</html>
