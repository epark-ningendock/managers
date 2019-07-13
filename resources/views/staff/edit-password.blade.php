@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-user"> パスワード設定</i>
        - 
      {{ $staff->name }}
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update.password', ['staff_id' => $staff->id]) }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    @include('staff.partials.edit-password-form-admin')
  </form>
@stop