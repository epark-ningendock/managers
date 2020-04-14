@extends('layouts.form')

@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        - 
        <span>医療機関スタッフ編集</span>
    </h1>
    <h5 align="right"><a href="{{ './manual/01_staff.pdf' }}" target="_blank">医療機関スタッフ管理の使い方</a></h5>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.update', $hospital_staff->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.form')
    </form>
@stop