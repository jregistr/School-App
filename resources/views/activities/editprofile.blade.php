@extends('layouts.main', ["index" => 0])

@section('stylesheets')
@endsection

@section('javascripts')
    <script>
        $(document).ready(function () {

            var schoolSelect = $('#school_id'),
                addOthers = $('#add-school-fields');

            if(parseInt(schoolSelect.val()) === -2) {
                addOthers.show();
            }

            schoolSelect.change(function () {
                var value = schoolSelect.val();
                if (parseInt(value) === -2) {
                    addOthers.show();
                } else {
                    addOthers.hide();
                }
            });
        });
    </script>
@endsection

@section('content')

    <?php
    $years = [
        'Freshman',
        'Sophomore',
        'Junior',
        'Senior'
    ];
    ?>
    <div class="row pad hidden-sm hidden-xs"></div>
    <div class="row">
        <div class="col-lg-2 col-md-2 hidden-sm hidden-xs"></div>

        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="alert alert-info alert-dismissable">
                <a class="panel-close close" data-dismiss="alert"></a>
                <span class="glyphicon glyphicon-user"></span>
                Edit your <strong>Profile</strong>.
            </div>
            <h3>Profile Information</h3>
            <form class="form-horizontal" role="form" method="POST" action="{{url('/profile')}}">
                {{ csrf_field() }}
                <input type="text" hidden name="student_id" value="{{$user->id}}"/>

                <div class="form-group {{ $errors->has('first') ? ' has-error' : '' }}">
                    <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">First Name</label>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <input class="form-control" type="text" name="first" placeholder="Enter your name"
                               value="{{$user->first_name ? $user->first_name : old('first') }}"/>
                    </div>
                    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs"></div>
                </div>
                <div class="form-group {{ $errors->has('last') ? ' has-error' : '' }}">
                    <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">Last Name</label>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <input class="form-control" type="text" placeholder="Enter your last name" name="last"
                               value="{{$user->last_name ? $user->last_name : old('last')}}"/>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('year') ? ' has-error' : '' }}">
                    <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">Year</label>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <select class="form-control" name="year">
                            @foreach($years as $year)
                                <option value="{{$year}}" {{($user->year == $year) ? "selected=\"selected\"" : ""}}>
                                    {{$year}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('major') ? ' has-error' : '' }}">
                    <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">Major</label>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <input class="form-control" type="text" placeholder="Enter major" name="major"
                               value="{{$user->major ? $user->major : old('major') }}"/>
                    </div>
                </div>

                <div class="form-group {{ $errors->has('school') ? ' has-error' : '' }}">
                    <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">School</label>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <select id="school_id" class="form-control" name="school">
                            <option value="-1">None</option>
                            @foreach($schools as $school)
                                <option value="{{$school->id}}" {{(!$errors->has('name') && $user->school_id == $school->id) ? "selected=\"selected\"" : ""}}>
                                    {{$school->name}}
                                </option>
                            @endforeach
                            <option value="-2" {{$errors->has('name') ? "selected=\"selected\"" : ""}}>
                                Other...specify
                            </option>
                        </select>
                    </div>
                </div>


                <div id="add-school-fields" style="display: none;">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">School Name</label>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input class="form-control" type="text" name="name"
                                   placeholder="Enter your school's name"
                                   value="{{old('name') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs"></div>
                    </div>

                    <div class="form-group {{ $errors->has('school_country') ? ' has-error' : '' }}">
                        <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">Country</label>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input class="form-control" type="text" name="school_country"
                                   placeholder="Enter your school's country"
                                   value="{{old('school_country') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs"></div>
                    </div>

                    <div class="form-group {{ $errors->has('school_state') ? ' has-error' : '' }}">
                        <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">State</label>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input class="form-control" type="text" name="school_state"
                                   placeholder="Enter your school's state"
                                   value="{{old('school_name') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs"></div>
                    </div>

                    <div class="form-group {{ $errors->has('school_city') ? ' has-error' : '' }}">
                        <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12">City</label>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input class="form-control" type="text" name="school_city"
                                   placeholder="Enter your school's city"
                                   value="{{old('school_city') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-8">
                        <input type="submit" class="btn btn-primary" value="Submit Changes">
                        <span></span>
                        <input type="reset" class="btn btn-default" value="Cancel">
                    </div>
                </div>

            </form>
        </div>

        <div class="col-lg-2 col-md-2 hidden-sm hidden-xs"></div>
    </div>
@endsection