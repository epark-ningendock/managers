@extends('layouts.form')

@section('content_header')
  <h1>受付メール設定</h1>
@stop

@section('form')
  {{ Form::open(['route' => array('reception-email-setting.update', $reception_email_setting->id), 'method' => 'post']) }}
    {{ method_field('PUT') }}
    @include('reception_email_setting.partials.form')
@stop
