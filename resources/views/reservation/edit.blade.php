@extends('layouts.form')

@section('content_header')
  <h1>    
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <i class="fa fa-book"> 受付編集</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('reservation.update', ['reservation' => $reservation->id]) }}">
  	{!! csrf_field() !!}
      {{ method_field('PATCH') }}
    @include('reservation.partials.edit-form')
  </form>
  @includeIf('reservation.partials.customer-script')
@stop
