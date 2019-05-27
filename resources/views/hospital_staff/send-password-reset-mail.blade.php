@extends('layouts.form')

@section('content_header')
    <h1>パスワードリセットメール送信</h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.send.password-reset') }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.send-reset-password')
    </form>
@stop