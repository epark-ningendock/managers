@extends('layouts.master')
<!-- ページの内容を入力 -->
@section('main-content')
    <!-- Error -->
    @include('layouts.partials.error_pan')
    @include('layouts.partials.message_lock')
    @yield('form')
@stop
