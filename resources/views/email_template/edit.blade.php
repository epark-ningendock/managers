@extends('layouts.form')

@section('content_header')
  <h1>テンプレート管理- {{ $email_template->title }}</h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('email-template.update', $email_template->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('email_template.partials.form')
    </form>
@stop