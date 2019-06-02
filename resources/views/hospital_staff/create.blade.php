@extends('layouts.form')

@section('content_header')
  <h1>医療機関スタッフを作成する</h1>
@stop

@section('form')
  <form method="POST" action="{{ url('/hospital-staff') }}">
    {{ csrf_field() }}
    @include('hospital_staff.partials.form')
  </form>
@stop