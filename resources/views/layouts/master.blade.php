<!-- adminlte::pageを継承 -->
@extends('adminlte::page')

@include('layouts.modal')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/app.css?q=1.0') }}">
@stop

<script src="{{ asset('js/app.js?q=1.0') }}"></script>
<script src="{{ asset('js/all.js?q=1.0') }}"></script>
