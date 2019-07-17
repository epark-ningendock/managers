@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('main-content')
  <div class="box box-primary">

    <div class="inner-box">

      <!-- Error -->
      {{--@include('layouts.partials.errorbag')--}}

      @yield('form')

    </div>

  </div>
@stop
