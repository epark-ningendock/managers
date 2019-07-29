@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      - 
      <span>医療機関スタッフ登録</span>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ url('/hospital-staff') }}">
    {{ csrf_field() }}
    @include('hospital_staff.partials.form')
  </form>
@stop