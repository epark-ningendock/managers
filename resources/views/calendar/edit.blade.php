@extends('layouts.form')

@section('content_header')
  <h1>カレンダー管理</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('calendar.update', $calendar->id) }}">
    {!! method_field('PATCH') !!}
    @include('calendar.partials.form')
  </form>
@stop