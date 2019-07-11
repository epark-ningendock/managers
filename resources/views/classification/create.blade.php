@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-book"> 検査コース分類管理</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('classification.store') }}">
    @include('classification.partials.form', [ 'type' => $type])
  </form>
@stop