@extends('layouts.master')

@section('content_header')
  <h1>
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <span> 検査コース管理</span>
      -
      <span> 登録</span>
  </h1>
@stop

<!-- ページの内容を入力 -->
@section('main-content')
  @include('layouts.partials.message')
  <form method="POST" action="{{ route('course.store') }}" enctype="multipart/form-data">
    {!! csrf_field() !!}
    @include('course.partials.form')
  </form>
@stop






