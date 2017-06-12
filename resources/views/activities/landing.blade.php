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
    {{--<link rel="stylesheet" href="/css/bootstrap.min.css"/>--}}

    {{--<link href="/css/register.css" rel="stylesheet">--}}
    <link href='https://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="icon" href="/images/graduation-school-hat.png"/>
    {{--<link rel="stylesheet" href="/css/landing.css"/>--}}
    <link rel="stylesheet" href="dist/css/bootstrap.css" />
    <link rel="stylesheet" href="css/landing.css" />
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>

<div class="container-fluid" id="background">

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{url("/")}}"><img src="images/graduation-school-hat.png"/> myAgenda</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse navbar-right">
                <ul class="nav navbar-nav">
                    <li><a href="{{url("/register")}}">Register</a></li>
                    <li><a href="{{url("/login")}}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="intro-header">
        {{--<div class="container">--}}

            <div class="row">
                <div class="col-lg-5"></div>
                <div class="col-lg-5">
                    <div class="intro-message">
                        <h1>Stay on task</h1>

                        <h3>Create and manage your schedules. Track your course progress during the semester</h3>
                        {{--<hr class="intro-divider">--}}

                        <div align="center" id="sign-in-btn"></div>
                    </div>
                </div>
                <div class="col-lg-2"></div>

            </div>

    </div>


</div>

<div class="section-a">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <hr class="section-heading-spacer">
                <div class="clearfix"></div>
                <h2 class="section-heading">What is myAgenda</h2>
                <p class="lead">
                    A simple and easy to use tool to generate schedules based on specified meeting times.
                </p>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="copyright text-muted small">Copyright &copy; 2017. All Rights Reserved</p>
            </div>
        </div>
    </div>
</footer>

{{--<script src="/js/jquery-3.2.0.min.js"></script>--}}
{{--<script src="/js/bootstrap.min.js"></script>--}}
{{--<script src="dist/js/common.js"></script>--}}

<script src="dist/extract/manifest.js"></script>
<script src="dist/extract/common-vendors.js"></script>
<script src="dist/js/common.js"></script>
</body>
</html>