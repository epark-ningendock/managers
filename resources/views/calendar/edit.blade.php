@extends('layouts.form')

@section('content_header')
  <h1>    
    <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
    -
    <i class="fa fa-calendar"> カレンダー管理</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('calendar.update', $calendar->id) }}">
    {!! method_field('PATCH') !!}
    @include('calendar.partials.form')
  </form>
@stop