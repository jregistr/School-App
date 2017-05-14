@extends('layouts.main', ["index" => 3])

@section('stylesheets')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
    <link rel="stylesheet" href="dist/css/create.css"/>
@endsection

@section('javascripts')
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>--}}
    {{--<script src="dist/js/create.js"></script>--}}
    {{--<script src="dist/extract/create-vendor.js"></script>--}}
    <script src="dist/js/create.js"></script>
@endsection


@section('content')
    <main>
        <div>
            <div class="row pad hidden-sm hidden-xs"></div>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#courses"><strong>View Courses</strong></a></li>
                        <li><a data-toggle="tab" href="#sections"><strong>View Course Sections</strong></a></li>
                        <li><a data-toggle="tab" href="#added"><strong>Generate</strong></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="courses" class="tab-pane fade in active">
                            <h3>HOME</h3>
                            <p>Some content.</p>
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
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h3>Add a course/activity</h3>
                    <form id="course-form" class="form-horizontal" role="form">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-lg-12">Subject</label>
                                <input class="form-control col-lg-12" type="text" name="subj" placeholder="CSC">
                            </div>
                            <div class="col-lg-8">
                                <label class="col-lg-12">Course number</label>
                                <input class="form-control col-lg-12" type="number" name="number" placeholder="495">
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="col-lg-12">crn</label>
                                <input class="form-control col-lg-12" type="number" name="crn" value="220234"
                                       placeholder="11111">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-lg-12">Credits</label>
                                <input class="form-control col-lg-12" type="number" name="credits" value="3"
                                       placeholder="3">
                            </div>
                        </div>

                    </form>

                    <h3>Sections</h3>
                    <div class="panel-group" id="accordion">

                    </div>


                    <form id="section-form" class="form-horizontal" role="form">
                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6">
                                <label class="col-lg-12">Instructor</label>
                                <input class="form-control col-lg-12" type="text" name="inst" placeholder="optional">
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <label class="col-lg-12">Location</label>
                                <input class="form-control col-lg-12" type="text" name="loc" placeholder="optional">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6">
                                <label class="col-lg-12">Start Time</label>
                                <input class="form-control col-lg-12" type="time" name="start">
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <label class="col-lg-12">End Time</label>
                                <input class="form-control col-lg-12" type="time" name="end">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="checkbox-inline"><input type="checkbox" name="sun">S</label>
                                <label class="checkbox-inline"><input type="checkbox" name="mon">M</label>
                                <label class="checkbox-inline"><input type="checkbox" name="tue">T</label>
                                <label class="checkbox-inline"><input type="checkbox" name="wed">W</label>
                                <label class="checkbox-inline"><input type="checkbox" name="thu">R</label>
                                <label class="checkbox-inline"><input type="checkbox" name="fri">F</label>
                                <label class="checkbox-inline"><input type="checkbox" name="sat">S</label>
                            </div>
                            {{--<div class="col-lg-12">--}}

                            {{--</div>--}}
                            {{--<div class="col-lg-12">--}}

                            {{--</div>--}}
                        </div>
                    </form>


                    <div class="row">
                        <div class="col-lg-6">
                            <button id="addSectionBtn" class="btn btn-success"><span
                                        class="glyphicon glyphicon-plus"></span>Add
                                Section
                            </button>
                        </div>
                        <div class="col-lg-6"></div>
                    </div>

                    <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            <button id="submitClassBtn" class="btn btn-primary form-control">Submit</button>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <button id="cancel-add-class-btn" class="btn btn-danger form-control">Cancel</button>
                        </div>
                    </div>

                    {{--<div class="form-horizontal" style="margin-top: 10px;">--}}
                    {{----}}
                    {{--</div>--}}

                </div>
            </div>
        </div>
    </main>
@endsection
