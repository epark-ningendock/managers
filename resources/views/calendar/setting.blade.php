@php
  use App\Enums\StaffStatus;
  use App\Enums\Authority;
  use \App\Enums\Permission;
@endphp

@extends('layouts.form')

@section('content_header')
  <h1>カレンダー管理</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.store') }}">
    <div class="box-body">
      {!! csrf_field() !!}
    </div>
  </form>
@stop
