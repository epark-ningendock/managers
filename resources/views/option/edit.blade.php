@php

    $params = [
                'delete_route' => 'option.destroy',
              ];
@endphp
@extends('layouts.form')

@section('content_header')
    <h1>    
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        -
        <span> オプション管理</span>
    </h1>
@stop

@section('form')
    <form method="post" action="{{ route('option.update',['id' => $option->id]) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @includeIf('option.partials.form')


        <div class="action-btn-wrapper text-center mb-5 pb-5">
            <a href="{{ route('option.index') }}" class="btn btn-default">戻る</a>
            <button class="btn btn-primary" type="submit">登録</button>
            <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $option->id }}">
                削除
            </button>
        </div>

    </form>
    <form id="delete-record-form" class="hide" method="POST"
          action="{{ route('option.destroy', ['id' => $option->id]) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
    </form>
@stop