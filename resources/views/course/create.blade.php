@extends('layouts.master')

@section('content_header')
  <h1>検査コース管理</h1>
@stop

<!-- ページの内容を入力 -->
@section('main-content')
  @include('layouts.partials.errorbag')
  <form method="POST" action="{{ route('course.store') }}">
    {!! csrf_field() !!}
    @include('course.partials.form')
  </form>
@stop






