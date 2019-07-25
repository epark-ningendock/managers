@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('main-content')

  <div class="box box-primary">

    @include('layouts.partials.message')
    <div class="inner-box">

      @yield('form')

    </div>

  </div>
@stop
