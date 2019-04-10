@extends('layouts.form')

@section('content_header')
  <h1>スタッフ情報</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update', $staff->id) }}">
    {!! method_field('PATCH') !!}
    @include('staff.partials.form')
  </form>
@stop
