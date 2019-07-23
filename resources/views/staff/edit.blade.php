@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-users"> スタッフ管理</i>
      -
      <i class="fa fa-user" style="font-size: 2.3rem;"> スタッフ編集</i>
      {{-- -
      <i class="fa fa-user"> {{ $staff->name }}</i> --}}
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update', $staff->id) }}">
    {!! method_field('PATCH') !!}
    @include('staff.partials.form')
  </form>
@stop
