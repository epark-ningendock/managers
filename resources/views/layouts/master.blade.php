<!-- adminlte::pageを継承 -->
@extends('adminlte::page')

@include('layouts.partials.modal')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/app.css?q=1.0') }}">
@stop

@section('js')
  <script src="{{ asset('js/app.js?q=1.0') }}"></script>
@stop

