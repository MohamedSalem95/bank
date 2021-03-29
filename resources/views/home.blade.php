@extends('layouts.app')

@section('content')

<h4> Welcome to Your Dashboard! </h4>
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@endsection
