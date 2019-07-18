@extends('layouts.form')

@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        - 
        <i class="fa fa-users"> 医療機関スタッフ管理</i>
        -
        <i class="fa fa-user"> {{ $hospital_staff->name }}</i>
    </h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.update', $hospital_staff->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.form')
    </form>
@stop