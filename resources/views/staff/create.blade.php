@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-users"> スタッフ管理</i>
      -
      <i class="fa fa-user" style="font-size: 2.3rem;"> スタッフ登録</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.store') }}">
    @include('staff.partials.form')
  </form>
@stop