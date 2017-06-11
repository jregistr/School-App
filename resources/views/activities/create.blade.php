@extends('layouts.main', ["index" => 3])

@section('stylesheets')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" >
    <link rel="stylesheet" href="dist/css/create.css"/>
@endsection

@section('javascripts')
    <script src="dist/js/create.js"></script>
@endsection

@section('content')
    <div>
        <div class="modal fade" id="sch-courseInfoModal" tabindex="-1" role="dialog" aria-labelledby="Course Info Modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sch-confirmModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;"
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
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sch-confirmModal2" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Confirm this action</h4>
                    </div>
                    <div class="modal-body">
                        <strong class="lead">Are you sure you want <b>remove</b> this course?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Confirm</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sch-confirmClearGens" class="modal fade" tabindex="-1" role="dialog" style="display: none;"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                            {{--<span aria-hidden="true">&times;</span>--}}
                        {{--</button>--}}
                        <h4 class="modal-title">Confirm this action</h4>
                    </div>
                    <div class="modal-body">
                        <strong class="lead">Are you sure you want to clear current generated schedules?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Confirm</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sch-confirmAddRem" class="modal fade" tabindex="-1" role="dialog" style="display: none;"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirm this action</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Yes</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sch-addEditModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Modify schedule</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sch-addFromListModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Modify schedule</h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#coursesScheduleView"><strong>Courses</strong></a></li>
                                <li><a data-toggle="tab" href="#sectionsScheduleView"><strong>Sections</strong></a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="coursesScheduleView" class="tab-pane fade in active">
                                    <div class="view-course-table">

                                    </div>
                                </div>
                                <div id="sectionsScheduleView" class="tab-pane fade">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="sch-courseInfoModal" tabindex="-1" role="dialog" aria-labelledby="Course Info Modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="sch-timeConflict" tabindex="-1" role="dialog" aria-labelledby="Time conflict modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Time conflict error</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Modals for schedule renderer -->

    <!-- PAGE! -->

    <div class="container-fluid ct-out">
        <div class="modal fade" id="courseInfoModal" tabindex="-1" role="dialog" aria-labelledby="Course Info Modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

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
                        <strong class="lead">Are you sure you want clear this list?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Confirm</button>
                        <button  type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>

        <div class="pad hidden-sm hidden-xs"></div>

        <div class=generate-form">
            <div class="">
                <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 generate-list-box">
                    <table id="generate-candidates" class="table borderless">
                        <thead>
                        <tr>
                            <th class="col-lg-8 col-md-8 col-sm-8 col-xs-8">Section</th>
                            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1">Required</th>
                            <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
            </div>


            {{--<div class="">--}}
                {{--<div class="col-lg-12 col-md-12 hidden-sm hidden-xs"></div>--}}
                {{--<div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>--}}
                {{--<div class="col-lg-10 col-md-10 col-sm-12" style="padding-left: 0; padding-right: 0; margin-top: 10px!important;">--}}
                    {{--<div id="creditLimitOuter" class="">--}}
                        {{--<form class="form-inline">--}}
                            {{--<div class="form-group">--}}
                                {{--<label for="email">Email address:</label>--}}
                                {{--<input type="email" class="form-control" id="email">--}}
                            {{--</div>--}}
                            {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>--}}
            {{--</div>--}}

            <div class="">
                <div class="col-lg-12 col-md-12 hidden-sm hidden-xs"></div>
                <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 10px!important; padding-left: 0!important; padding-right: 0!important;">
                    <div class="generate-form-small-controll-btn">
                        <div id="creditLimitOuter" class="input-group">

                        </div>
                    </div>
                </div>
                {{--<div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>--}}
            </div>

            <div class="">
                <div class="col-lg-12 col-md-12 hidden-sm hidden-xs"></div>
                <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="margin-top: 10px!important; padding-left: 0!important; padding-right: 0!important;">
                    <div class="generate-form-small-controll-btn">
                        <button id="addNew" type="button" class="btn btn-default col-sm-4 col-xs-4">Add New</button>
                        <button id="clearAll" type="button" class="btn btn-default col-sm-2 col-xs-2">Clear</button>
                        <button id="genSch" type="button" class="btn btn-default col-sm-6 col-xs-6">Generate</button>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
            </div>
        </div>

        <div class="" style="margin-top: 20px; padding-top: 20px">
            <div class="col-lg-12 col-md-12 hidden-sm hidden-xs"></div>
            <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="padding-left: 0; padding-right: 0; padding-top: 10px">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#courses"><strong>Courses</strong></a></li>
                    <li><a data-toggle="tab" href="#sections"><strong>Sections</strong></a></li>
                    <li><a data-toggle="tab" href="#added"><strong>Schedules</strong></a></li>
                </ul>
                <div class="tab-content">
                    <div id="courses" class="tab-pane fade in active">
                        <div class="view-course-table">

                        </div>
                    </div>
                    <div id="sections" class="tab-pane fade">
                    </div>
                    <div id="added" class="tab-pane fade">

                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
        </div>

    </div>
@endsection
