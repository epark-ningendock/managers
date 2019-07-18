@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-users"> スタッフ管理</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.store') }}">
    @include('staff.partials.form')
  </form>
@stop