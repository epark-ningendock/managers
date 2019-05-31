@extends('layouts.form')

@section('content_header')
    <h1>パスワードリセットメール送信</h1>
@stop

@section('form')
    <form method="GET"  action="{{ route('hospital-staff.send.password-reset') }}">
        {{ csrf_field() }}
        @include('hospital_staff.partials.send-reset-password')
    </form>
@stop