@extends('layouts.form')

@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        - 
        <i class="fa fa-user"> パスワードの変更</i>
    </h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.update.password', $hospital_staff->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.edit-password-form')
    </form>
@stop