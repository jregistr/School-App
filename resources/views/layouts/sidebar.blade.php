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
    <link rel="stylesheet" href="/css/bootstrap.min.css"/>

    <link href="/css/sidemenu.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="icon" href="/images/favicon.png" />
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div class="register">
    <div class="wrap">
        <nav class="nav-bar navbar-inverse" role="navigation">
            <div id ="top-menu" class="container-fluid active">
                <a class="navbar-brand" href="#">Brand</a>
                <ul class="nav navbar-nav">
                    <form id="qform" class="navbar-form pull-left" role="search">
                        <input type="text" class="form-control" placeholder="Search" />
                    </form>
                    <li class="dropdown movable">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="fa fa-4x fa-child"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#"><span class="fa fa-user"></span>My Profile</a></li>
                            <li><a href="#"><span class="fa fa-gear"></span>Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><span class="fa fa-power-off"></span>Logout</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
        <aside id="side-menu" class="aside" role="navigation">
            <ul class="nav nav-list accordion">
                <li class="nav-header">
                    <div class="link"><i class="fa fa-lg fa-globe"></i>Portal<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Administration</a></li>
                    </ul>
                </li>

                <li class="nav-header">
                    <div class="link"><i class="fa fa-lg fa-users"></i>Users<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <li><a href="#">Users</a></li>
                        <li><a href="#">New User</a></li>
                    </ul>
                </li>

                <li class="nav-header">
                    <div class="link"><i class="fa fa-cloud"></i>Sites<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <li><a href="#">Search Sites</a></li>
                        <li><a href="#">New Site</a></li>
                        <li><a href="#">Jobs</a></li>
                    </ul>
                </li>

                <li class="nav-header">
                    <div class="link"><i class="fa fa-lg fa-map-marker"></i>Zones<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <li><a href="#">Search Zones</a></li>
                        <li><a href="#">New Zone</a></li>
                    </ul>
                </li>

                <li class="nav-header">
                    <div class="link"><i class="fa fa-lg fa-file-image-o"></i>Reports<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <li><a href="#">Entries</a></li>
                        <li><a href="#">Redirects</a></li>
                        <li><a href="#">Pingbacks</a></li>
                        <li><a href="#">Tags</a></li>
                    </ul>
                </li>

            </ul>
        </aside>

        <!--Body content-->
        <div class="content">
            <div class="top-bar">
                <a href="#menu" class="side-menu-link burger">
                    <span class='burger_inside' id='bgrOne'></span>
                    <span class='burger_inside' id='bgrTwo'></span>
                    <span class='burger_inside' id='bgrThree'></span>
                </a>
            </div>
            <section class="content-inner">
                <h2>Sample</h2>
                <h3>A responsive Top and Side Menu, resize your browser to find out</h3>
            </section>
        </div>

    </div>

    @yield('content')
</div>

<!-- Scripts -->
<script src="/js/app.js"></script>
<script src="/js/jquery-3.2.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>
