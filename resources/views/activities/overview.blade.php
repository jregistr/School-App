@extends('layouts.main', ["index" => 1])

@section('stylesheets')
    <link rel="stylesheet" href="dist/overview.bundle.css">
@endsection

@section('javascripts')
    <script src="dist/overview.bundle.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Overview page</h1>
            </div>
        </div>
    </div>
@endsection