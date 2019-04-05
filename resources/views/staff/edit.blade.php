@extends('layouts.form', [ 'box_title' => 'スタッフ管理' ])

@section('content_header')
  <h1>スタッフ情報</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update', $staff->id) }}">
    {!! method_field('PATCH') !!}
    @include('staff.partial.form')
  </form>
@stop
