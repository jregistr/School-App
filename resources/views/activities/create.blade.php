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
    <div class="row pad hidden-sm hidden-xs"></div>
{{--    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#courses"><strong>View Courses</strong></a></li>
                <li><a data-toggle="tab" href="#sections"><strong>View Course Sections</strong></a></li>
                <li><a data-toggle="tab" href="#added"><strong>Generate</strong></a></li>
            </ul>
            <div class="tab-content">
                <div id="courses" class="tab-pane fade in active">

                </div>
                <div id="sections" class="tab-pane fade">
                    <h3>Menu 1</h3>
                    <p>Some content in menu 1.</p>
                </div>
                <div id="added" class="tab-pane fade">
                    <h3>Menu 2</h3>
                    <p>Some content in menu 2.</p>
                </div>
            </div>
            <div class="panel-footer">

            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <h3>Add a course/activity</h3>
            <div id="add-class">

            </div>
        </div>
    </div>--}}

    {{--<h3>Add a course/activity</h3>--}}
    <div class="row generate-form">
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 generate-list-box" style="padding-top: 10px">
            {{--<table id="generate-candidates" class="table borderless">--}}
                {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th class="col-lg-8 col-md-8 col-sm-8 col-xs-8">Section</th>--}}
                        {{--<th class="col-lg-1 col-md-1 col-sm-1 col-xs-1">Required</th>--}}
                        {{--<th class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></th>--}}
                    {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}

                {{--</tbody>--}}
            {{--</table>--}}
            <div id="generate-candidates-list" role="tablist" class="panel-group" aria-multiselectable="true">

            </div>
        </div>
        <div class="col-lg-2 col-md-2 hidden-sm hidden-xs">
            {{--<div>--}}
                {{--<div class="form-group generate-form-lg-control-btn">--}}
                    {{--<button id="add1" class="btn btn-success form-control">Add new</button>--}}
                    {{--<button id="clear1" class="btn btn-danger form-control">Clear All</button>--}}
                    {{--<button id="gen1" class="btn btn-primary form-control">Generate</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        <div class="" style="margin-top: 10px!important;">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="generate-form-small-controll-btn">
                    <button id="add2" type="button" class="btn btn-default col-sm-4 col-xs-4">Add new</button>
                    <button id="clear2" type="button" class="btn btn-default col-sm-2 col-xs-2">Clear</button>
                    <button id="gen2" type="button" class="btn btn-default col-sm-6 col-xs-6">Generate</button>
                </div>
            </div>
        </div>
    </div>

@endsection
