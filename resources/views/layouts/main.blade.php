<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Set The Title') }}</title>

    <link rel="icon" href="/images/graduation-school-hat.png"/>
    <link rel="stylesheet" href="dist/css/bootstrap.css" />
    <link rel="stylesheet" href="dist/css/mainTemplate.css" />
@yield('stylesheets')

<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

        window.student_id = {!! auth()->user()->id !!};

    </script>
</head>
<body>

<nav class="navbar navbar-default hidden-lg hidden-md hidden-sm" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button id="small-nav-toggle" type="button" class="navbar-toggle active" data-toggle="collapse" data-target="#navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div>
        <a class="brand">
            <img src="images/logo.png" />
        </a>
    </div>
</nav>

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

<script src="dist/js/common.js"></script>
@yield('javascripts')
</body>
</html>
