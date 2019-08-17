@php
  use \App\Hospital;
@endphp

@extends('layouts.form')

@section('content_header')
  <h1>    
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <span> 受付メール設定</span>
  </h1>
@stop

@section('form')
  {{ Form::open(['route' => array('hospital-email-setting.update', $hospital_email_setting->id), 'method' => 'post']) }}
    {{ method_field('PUT') }}
    @include('hospital_email_setting.partials.form')
@stop
