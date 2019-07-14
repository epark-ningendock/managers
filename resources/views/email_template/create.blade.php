@extends('layouts.form')

@section('content_header')
  <h1>    
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <i class="fa fa-gears"> テンプレート管理</i>
  </h1>
@stop

@section('form')
  {{ Form::open(['route' => 'email-template.store', 'method' => 'post']) }}
    @include('email_template.partials.form')
@stop