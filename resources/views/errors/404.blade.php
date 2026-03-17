<!-- resources/views/errors/404.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1 class="display-3">404</h1>
        <p class="lead">Sorry, the page you are looking for could not be found.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
    </div>
@endsection
