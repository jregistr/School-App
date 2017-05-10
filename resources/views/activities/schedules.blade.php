@extends('layouts.main', ["index" => 2])

@section('stylesheets')
    <link rel="stylesheet" href="dist/css/schedules.css"/>
@endsection

@section('javascripts')
@endsection

@section('content')
    {{--<div class="container">--}}
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 rm-pad">
            <div class="panel panel-default">
                <div class="panel-heading schedule-bar">
                    <strong class="schedule-title">My Classes</strong>
                    <div class="schedule-button-group dropdown">
                        <button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                            Schedules
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right scrollable-menu" role="menu" aria-labelledby="dropdownMenu1">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="calender"></div>
    </div>
    {{--</div>--}}
@endsection