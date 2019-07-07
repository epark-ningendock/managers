@extends('layouts.master')

@section('content_header')
  <h1>
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <i class="fa fa-book"> 検査コース管理</i>
  </h1>
@stop

<!-- ページの内容を入力 -->
@section('main-content')
  @include('layouts.partials.errorbag')
  <form method="POST" action="{{ route('course.store') }}">
    {!! csrf_field() !!}
    @include('course.partials.form')
  </form>
@stop






