@extends('layouts.main', ["index" => 3])

@section('stylesheets')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" >
    <link rel="stylesheet" href="dist/css/create.css"/>
@endsection

@section('javascripts')
    <script src="dist/js/create.js"></script>
@endsection


@section('content')
    <div class="row pad hidden-sm hidden-xs"></div>
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
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
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h3>Add a course/activity</h3>
            <div id="add-class">

            </div>


        </div>
    </div>

@endsection
