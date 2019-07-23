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
  <form method="POST" action="{{ route('course.update', $course->id) }}">
    {!! csrf_field() !!}
    {!! method_field('PATCH') !!}
    @include('course.partials.form')
  </form>
@stop






