@extends('layouts.form')

@section('content_header')
    <h1>基本情報</h1>
@stop

@section('form')

    @includeIf('hospital.partials.nav-bar')
<h1>here</h1>
{{--    <form id="contract-form" class="form-horizontal" method="post" action="{{ route('contract.store') }}">--}}
{{--        {{ csrf_field() }}--}}
{{--        <h5 class="sm-title">契約情報</h5>--}}
{{--        @includeIf('hospital.partials.form.contract-form')--}}
{{--    </form>--}}
{{--    @includeIf('hospital.partials.form-script-style')--}}

@stop