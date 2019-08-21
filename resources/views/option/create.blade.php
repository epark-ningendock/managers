@extends('layouts.form')

@section('content_header')
    <h1>    
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        -
        <span> オプション管理</span>
    </h1>
@stop

@section('form')
    <form method="post" action="{{ route('option.store') }}">
        {{ csrf_field() }}

        @includeIf('option.partials.form')

        <div class="action-btn-wrapper text-center mb-5 pb-5">
            <a href="{{ route('option.index') }}" class="btn btn-default">戻る</a>
            <button class="btn btn-primary" type="submit">登録</button>
        </div>

    </form>

@stop