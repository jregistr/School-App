@extends('layouts.main', ["index" => 2])

@section('stylesheets')
    <link rel="stylesheet" href="dist/schedules.bundle.css">
@endsection

@section('javascripts')
    <script src="dist/schedules.bundle.js"></script>
@endsection

@section('content')
    {{--<div class="container">--}}
    <div class="row">
        <div class="">
            <div class="panel panel-default">
                <div class="panel-heading">Schedules</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="calender"></div>
    </div>
    {{--</div>--}}
@endsection