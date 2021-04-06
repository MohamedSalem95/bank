@extends('layouts.app')

@section('content')
    <p> Edit Exhange Here!! </p>
    <edit-exchange-component :id="{{ $exchange->id }}"></edit-exchange-component>
@stop