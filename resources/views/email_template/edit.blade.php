@extends('layouts.form')

@section('content_header')
  <h1>    
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <span>テンプレート管理</span>
  </h1>
@stop

@section('form')
  {{ Form::open(['route' => array('email-template.update', $email_template->id), 'method' => 'post']) }}
    {{ method_field('PUT') }}
    @include('email_template.partials.form')
@stop