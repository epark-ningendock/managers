@extends('layouts.form')

@section('content_header')
    <h1>編集 - 基本情報</h1>
@stop

@section('form')

    @includeIf('hospital.partials.nav-bar')

    <form id="contract-form" class="form-horizontal h-adr" method="POST" action="{{ route('hospital.update',['hospital' => $hospital]) }}">
        {{method_field('PUT')}}
        {{ csrf_field() }}
        <h5 class="sm-title">基本情報</h5>
        @includeIf('hospital.partials.hospital-form')
    </form>

@stop

@push('css')
    <style>
        .temp-fixed-alert {
            margin-left: -30px;
            margin-top: -30px;
            margin-right: -30px;
        }
        .temp-fixed-alert .alert {
            position: relative;
            width: 100%;
            left: 0;
            top: 0;
        }
    </style>
@endpush