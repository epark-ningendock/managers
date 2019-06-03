@extends('layouts.form')

@section('content_header')
    <h1>医療機関スタッフ - {{ $hospital_staff->name }}</h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.update', $hospital_staff->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.form')
    </form>
@stop