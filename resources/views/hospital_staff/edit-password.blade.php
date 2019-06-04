@extends('layouts.form')

@section('content_header')
    <h1>医療機関スタッフ名 ： {{ $hospital_staff->name }}</h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.update.password', $hospital_staff->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.edit-password-form')
    </form>
@stop