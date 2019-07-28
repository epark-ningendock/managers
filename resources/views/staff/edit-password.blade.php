@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-user"> パスワード設定</i>
        - 
      <i class="fa fa-user"> {{ $staff->name }}</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update.password', ['staff_id' => $staff->id]) }}">
    <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    @include('staff.partials.edit-password-form-admin')
  </form>
@stop