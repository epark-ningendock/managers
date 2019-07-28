@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-user"> パスワード設定</i>
        - 
      {{ Auth::user()->name }}
  </h1>
@stop

@section('form')
    <div class="form-entry">
  <form method="POST" action="{{ route('staff.update.password-personal') }}">
    <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
    {{ csrf_field() }}
    @include('staff.partials.edit-password-form')
  </form>
    </div>
@stop