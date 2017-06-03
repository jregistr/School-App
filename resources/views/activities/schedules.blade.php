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
                    <strong class="lead">Are you sure you want to <b>Permanently delete</b> this schedule?</strong>
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
@endsection