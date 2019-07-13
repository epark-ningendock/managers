@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-user"> パスワード設定</i>
        - 
      {{ Auth::user()->name }}
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update.password-personal') }}">
    {{ csrf_field() }}
    @include('staff.partials.edit-password-form')
  </form>
@stop