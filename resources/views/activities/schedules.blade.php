@extends('layouts.main', ["index" => 2])

@section('stylesheets')
    {{--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css" />
    <link rel="stylesheet" href="dist/css/schedule.css"/>
@endsection

@section('javascripts')
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>--}}
    <script src="dist/js/schedule.js"></script>
@endsection

@section('content')
    <div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Confirm this action</h4>
                </div>
                <div class="modal-body">
                    <strong class="lead">Are you sure you want to delete this schedule?</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Confirm</button>
                    <button  type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="scheduleToolbarParent" class="container-fluid schedule-page">

    </div>

    <div id="scheduleComponentParent" class="container-fluid schedule-page">

    </div>

    {{--<div class="pull-right">--}}
        {{--<div class="dropdown" style="width: 400px">--}}
            {{--<button style="width: 100%!important;" class="text-left btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">--}}
                {{--<span class="pull-left" style="max-width: 90%; overflow: hidden; text-overflow:ellipsis;">--}}
                    {{--Tutorials nfjnjkfnakjwndkjnwajkf nawjn kjwafkjawnfkjnawkjf nawkjfnakjwnfkjawnf kjawnkjf nawkjf--}}
                    {{--kjnfkjawnkjfnawkjfn kjawnfkj anwkj nawkjnfkjanwfkjawn fkjw ajk fa--}}
                {{--</span>--}}
                {{--<span class="pull-right"><span class="caret"></span></span></button>--}}
            {{--<ul style="width: 100%!important;" class="dropdown-menu dropdown-menu-left" role="menu" aria-labelledby="menu1">--}}
                {{--<li role="presentation"><a role="menuitem" tabindex="-1" href="#">HTML</a></li>--}}
                {{--<li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSS</a></li>--}}
                {{--<li role="presentation"><a role="menuitem" tabindex="-1" href="#">JavaScript</a></li>--}}
                {{--<li role="presentation" class="divider"></li>--}}
                {{--<li role="presentation"><a role="menuitem" tabindex="-1" href="#">About Us</a></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
    {{--</div>--}}





    {{--<div class="navbar navbar-default schedule-toolbar-navbar">--}}
        {{--<div class="container-fluid">--}}
            {{--<div class="pull-left">--}}
                {{--<button class="navbar-btn btn btn-danger">Delete</button>--}}
            {{--</div>--}}
            {{--<div class="nav navbar-nav navbar-right">--}}
                {{--<form class="navbar-form">--}}
                    {{--<div class="form-group">--}}
                        {{--<input type="text" class="form-control" name="username" placeholder="Username">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<input type="text" class="form-control" name="password" placeholder="Password">--}}
                    {{--</div>--}}
                    {{--<button type="submit" class="btn btn-default">Sign In</button>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer">--}}
        {{--<div class="pull-left">--}}
            {{--<button class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>--}}
        {{--</div>--}}
        {{--<div class="form-inline pull-right">--}}

        {{--</div>--}}
    {{--</div>--}}


    {{--<div class="row">

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
    </div>--}}
@endsection