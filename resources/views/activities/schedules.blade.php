@extends('layouts.main', ["index" => 2])

@section('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css" />
    <link rel="stylesheet" href="dist/css/schedules.css"/>
@endsection

@section('javascripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="dist/js/schedule.js"></script>
@endsection

@section('content')
    {{--<div class="container">--}}
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 rm-pad">
            <div class="panel panel-default">
                <div class="panel-heading schedule-bar">

                    <strong id="setting-bar-schedule-name" class="schedule-title">My Classes</strong>
                    <input style="display: none" class="input-sm schedule-title" id="setting-bar-schedule-name-edit" type="text" placeholder="name" />

                    <div class="schedule-button-group dropdown">
                        <button id="setting-bar-edit-btn" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>
                        <button style="display: none" id="setting-bar-save-btn" class="btn btn-default"><span class="glyphicon glyphicon-floppy-save"></span></button>

                        <button style="display: none;" id="setting-bar-star" class="btn btn-default"><span class="glyphicon glyphicon-star-empty"></span></button>
                        <button style="display: none;" id="setting-bar-delete-btn" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
                        <button id="setting-bar-schedules-btn" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                            Schedules
                            <span class="caret"></span>
                        </button>
                        <ul id="setting-bar-schedules-list" class="dropdown-menu pull-right scrollable-menu" role="menu" aria-labelledby="dropdownMenu1">
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="parentDom">
        <div id="calendar"></div>
    </div>
    {{--</div>--}}
@endsection