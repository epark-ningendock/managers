@extends('layouts.form')

@section('content_header')
  <h1>テンプレート管理</h1>
@stop

@section('form')
  <form method="POST" action="{{ url('/email-template') }}">
    {{ csrf_field() }}
    @include('email_template.partials.form')
  </form>
@stop