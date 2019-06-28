@extends('layouts.form')

@section('content_header')
    <h1>こだわり情報 - {{ $hospital->name }}</h1>
@stop

@section('form')
  {{ Form::open(['route' => 'hospital.attention-information.store', 'method' => 'post']) }}
    @include('hospital.partials.attention-information-form')
@stop