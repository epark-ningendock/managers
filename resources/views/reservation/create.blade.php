@extends('layouts.form')

@section('content_header')
  <h1>受信 &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('reservation.store') }}">
  	{!! csrf_field() !!}
    @include('reservation.partials.form')
  </form>
  @includeIf('reservation.partials.customer-script')
@stop