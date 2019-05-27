@extends('layouts.form')

@section('content_header')
    <h1>パスワードリセット画面</h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.reset.password') }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.reset-password')
    </form>
@stop