@extends('layouts.form')

@section('content_header')
  <h1>テンプレート管理- {{ $email_template->title }}</h1>
@stop

@section('form')
  {{ Form::open(['route' => array('email-template.update', $email_template->id), 'method' => 'post']) }}
    {{ method_field('PUT') }}
    @include('email_template.partials.form')
@stop