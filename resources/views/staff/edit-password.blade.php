@extends('layouts.form')

@section('content_header')
  <h1>パスワードを変更する</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update.password', ['staff_id' => $staff_id]) }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    @include('staff.partials.edit-password-form')
  </form>
@stop