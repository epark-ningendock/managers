@extends('layouts.master')
<!-- ページの内容を入力 -->
@section('main-content')
    <!-- Error -->
    @include('layouts.partials.error_pan')
    @include('layouts.partials.message')
    @yield('form')
@stop
