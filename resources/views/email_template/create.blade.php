@extends('layouts.form')

@section('content_header')
  <h1>テンプレート管理 &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
@stop

@section('form')
  {{ Form::open(['route' => 'email-template.store', 'method' => 'post']) }}
    @include('email_template.partials.form')
@stop