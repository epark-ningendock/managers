<!-- adminlte::pageを継承 -->
@extends('adminlte::page')

@section('content')
  @yield('main-content')
  @include('layouts.partials.modal')
@stop

@section('css')
  <link rel="stylesheet" href="{{ asset('css/app.css?q=1.2') }}">
@stop

@section('js')
  <script src="{{ asset('js/app.js?q=1.2') }}"></script>
  @yield('script')
@stop

