<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Set The Title') }}</title>

    <!-- Styles -->
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"--}}
    {{--integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"--}}
    {{--crossorigin="anonymous">--}}

    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/main-template.css" rel="stylesheet">
    <link rel="icon" href="/images/graduation-school-hat.png"/>
@yield('stylesheets')

<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>

<div id="wrapper" class="">

    <div id="sidebar-wrapper">
        <ul id="sidebar-menu" class="sidebar-nav">
            <li class="sidebar-brand">
                <a id="sidebar-menu-toggle" href="#">
                    <img src="/images/logo.png" />
                    <span id="main-icon" class="glyphicon glyphicon-align-justify"></span>
                </a>
            </li>
        </ul>
        <ul class="sidebar-nav" id="sidebar">
            <li>
                <a href="/profile" class="{{$index == 0 ? "active" : ""}}">
                    Account<span class="side-icon glyphicon glyphicon-user"></span>
                </a>
                <div class="sub-pairs">
                    <div>
                        {{--<h5>Name</h5>--}}
                        <h5>Jeff</h5>
                    </div>
                    <div>
                        {{--<h5>Major</h5>--}}
                        <h5>Computer Science</h5>
                    </div>
                    <div>
                        {{--<h5>Year</h5>--}}
                        <h5>Senior</h5>
                    </div>
                </div>
            </li>
            <li>
                <a href="/overview" class="{{$index == 1 ? "active" : ""}}">Overview
                    <span class="side-icon glyphicon glyphicon-eye-open"></span>
                </a>
            </li>
            <li>
                <a href="schedules" class="{{$index == 2 ? "active" : ""}}">
                    Schedules
                    <span class="side-icon glyphicon glyphicon-calendar"></span>
                </a>
                <div>
                    {{--<h6 style="visibility: hidden">.</h6>--}}
                    <h6>d</h6>
                    <h6>d</h6>
                </div>
            </li>
            <li>
                <a href="/create" class="{{$index == 3 ? "active" : ""}}">
                    Create
                    <span class="side-icon glyphicon glyphicon-plus-sign"></span>
                </a>
            </li>
            <li>
                <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    Sign Out
                    <span class="side-icon glyphicon glyphicon-log-out"></span>
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>

    <div id="content-wrapper">
        <div class="page-content-wrapper">
            @yield('content')
        </div>
    </div>

</div>


<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="js/ app.js"></script>
<script src="js/main-template.js"></script>
@yield('javascripts')
</body>
</html>
