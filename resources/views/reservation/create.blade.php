@extends('layouts.form')

@section('content_header')
  <h1>    
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <i class="fa fa-book"> 受付登録</i>
  </h1>
  <h5 align="right"><a href="{{ '/manual/06_reception.pdf' }}" target="_blank">受付の使い方</a></h5>
@stop

@section('form')
  <form method="POST" action="{{ route('reservation.store') }}">
  	{!! csrf_field() !!}
    @include('reservation.partials.form')
  </form>
  @includeIf('reservation.partials.customer-script')
@stop