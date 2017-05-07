@extends('layouts.main', ["index" => 2])

@section('stylesheets')
    <link rel="stylesheet" href="css/dashboard.css">
@endsection

@section('javascripts')
    <script src="js/dashboard.js"></script>
@endsection


@section('content')
    {{--<div class="container">--}}
    <div class="row">
        <div class="">
            <div class="panel panel-default">
                <div class="panel-heading">Schedules</div>

                <div class="panel-body">
                    You are logged in!
                    <ul>
                        <li>
                            <a href="{{ url('/logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <strong>Logout</strong>
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