@extends('layouts.form')

@section('content_header')
  <h1>
    <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
    -
    <span>カレンダー管理</span>
  </h1>
@stop

@section('form')
  <div class="form-entry">
    <form method="POST" action="{{ route('calendar.update', $calendar->id) }}">
      {!! method_field('PATCH') !!}
      @include('calendar.partials.form')
    </form>
  </div>
@stop