@extends('layouts.form')

@section('content_header')
  <h1>カレンダー管理</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('calendar.store') }}">
    @include('calendar.partials.form')
  </form>
@stop