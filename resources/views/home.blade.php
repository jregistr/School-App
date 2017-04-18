@extends('layouts.main')

@section('stylesheets')
    <link rel="stylesheet" href="css/dashboard.css">
@endsection

@section('javascripts')
    <script src="js/dashboard.js"></script>
@endsection

@section('content')
    {{--<div class="container">--}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                    <ul>
                        <li>
                            <a href="{{ url('/logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{--</div>--}}
@endsection
