@extends('layouts.form')

@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        - 
        <i class="fa fa-user"> パスワードの変更</i>
        - 
        <i class="fa fa-user"> {{ $hospital_staff->name }}</i>
    </h1>
@stop

@section('form')
    <form method="POST"  action="{{ route('hospital-staff.update.password', $hospital_staff->id) }}">
        <input type="hidden" name="updated_at" value="{{ isset($hospital_staff) ? $hospital_staff->updated_at : null }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('hospital_staff.partials.edit-password-form')
    </form>
@stop