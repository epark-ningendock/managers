@extends('layouts.master')
<!-- ページの内容を入力 -->
@section('main-content')
    <!-- Error -->
    @include('layouts.partials.error_message_show')

    @yield('form')
@stop
