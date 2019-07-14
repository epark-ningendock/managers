@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      - 
      <i class="fa fa-users"> 医療機関スタッフ管理</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ url('/hospital-staff') }}">
    {{ csrf_field() }}
    @include('hospital_staff.partials.form', ['submit' => '新規登録'])
  </form>
@stop