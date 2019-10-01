@extends('layouts.form')

@section('content_header')
    <h1>編集 - 基本情報</h1>
@stop

@section('form')

    @include('layouts.partials.error_pan')
    @includeIf('hospital.partials.nav-bar')

    <form id="contract-form" class="form-horizontal h-adr" method="POST" action="{{ route('hospital.update',['hospital' => $hospital]) }}">
        @includeIf('hospital.partials.hospital-form')
        {{method_field('PUT')}}
        {{ csrf_field() }}
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