@extends('layouts.form')

@section('content_header')
    <h1>こだわり情報 - {{ $hospital->name }}</h1>
@stop

@section('form')
  @includeIf('hospital.partials.nav-bar')
  
  {{ Form::open(['url' => route('hospital.attention.store', $hospital->id), 'method' => 'post']) }}
    @include('hospital.partials.attention-form')
@stop