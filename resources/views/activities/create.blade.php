@extends('layouts.main', ["index" => 3])

@section('stylesheets')
    <link rel="stylesheet" href="dist/create.bundle.css">
@endsection

@section('javascripts')
    <script src="dist/create.bundle.js"></script>
@endsection


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Create</h1>
            </div>
        </div>
    </div>
@endsection
