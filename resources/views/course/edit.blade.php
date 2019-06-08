@extends('layouts.master')

@section('content_header')
  <h1>検査コース管理</h1>
@stop

<!-- ページの内容を入力 -->
@section('main-content')
  @include('layouts.partials.errorbag')
  <form method="POST" action="{{ route('course.update', $course->id) }}">
    {!! csrf_field() !!}
    {!! method_field('PATCH') !!}
    @include('course.partials.form')
  </form>
@stop






