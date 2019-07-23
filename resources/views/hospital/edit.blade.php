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
        @includeIf('hospital.partials.form.hospital-form')
    </form>

@stop
