@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-users"> スタッフ管理</i>
      -
      <span>スタッフ編集</span>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update', $staff->id) }}">
    {!! method_field('PATCH') !!}
    @include('staff.partials.form')
  </form>
@stop
