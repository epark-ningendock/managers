@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-book"> 検査コース分類管理</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('classification.update', $classification->id) }}">
    {!! method_field('PATCH') !!}
    @include('classification.partials.form', [ 'type' => $type])
  </form>
@stop