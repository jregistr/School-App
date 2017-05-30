@extends('layouts.main', ["index" => 3])

@section('stylesheets')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="dist/css/create.css"/>
@endsection

@section('javascripts')
    <script src="dist/js/create.js"></script>
@endsection


@section('content')

    <!-- Button trigger modal -->
    {{--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">--}}
        {{--Launch demo modal--}}
    {{--</button>--}}
    <!-- Modal -->
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

    <div class="row pad hidden-sm hidden-xs"></div>

    <div class="row generate-form">
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

        <div class="col-lg-12 col-md-12 hidden-sm hidden-xs"></div>
        <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="margin-top: 10px!important;">
            <div class="generate-form-small-controll-btn">
                <button id="addNew" type="button" class="btn btn-default col-sm-4 col-xs-4">Add New</button>
                <button id="clearAll" type="button" class="btn btn-default col-sm-2 col-xs-2">Clear</button>
                <button id="genSch" type="button" class="btn btn-default col-sm-6 col-xs-6">Generate</button>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#courses"><strong>Courses</strong></a></li>
                <li><a data-toggle="tab" href="#sections"><strong>Sections</strong></a></li>
                <li><a data-toggle="tab" href="#added"><strong>Schedules</strong></a></li>
            </ul>
            <div class="tab-content">
                <div id="courses" class="tab-pane fade in active">
                    {{--<div class="table-filters">--}}
                        {{--<div class="checkbox">--}}
                            {{--<label style="margin-right: 10px">--}}
                                {{--<input type="checkbox"> Added by me--}}
                            {{--</label>--}}
                            {{--<label>--}}
                                {{--<input type="checkbox"> In my school--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="view-course-table">

                    </div>
                </div>
                <div id="sections" class="tab-pane fade">
                    {{--<div class="">--}}
                        {{--<div class="col-xs-12" style="">--}}
                            {{--<div class="section-card">--}}
                                {{----}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
                <div id="added" class="tab-pane fade">
                    <h3>Menu 2</h3>
                    <p>Some content in menu 2.</p>
                    <div class="text">
                        Some really long text!  Some really long text!  Some really long text!  Some really long text! The end! Some really long text! The end! Some really long text! The end! Some really long text! The end!
                    </div>
                </div>
            </div>
            {{--<div class="panel-footer">--}}

            {{--</div>--}}
        </div>
        <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
    </div>

@endsection
