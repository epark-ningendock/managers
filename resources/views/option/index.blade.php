@php
    use \App\Enums\HospitalEnums;
    $params = [
        'delete_route' => 'option.destroy'
    ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>    
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        -
        <span> オプション管理</span>
    </h1>
@stop

@section('search')
    <div class="action-btn-wrapper text-right m-4">

        <a href="{{ route('option.create') }}" class="btn btn-primary">
            オプション登録
        </a>

        <a href="{{ route('option.sort') }}" class="btn btn-primary">
            オプション並べ替え
        </a>

    </div>
@stop

@section('table')
    <div class="table-responsive">
        @include('layouts.partials.pagination-label', ['paginator' => $options])
        {{ $options->appends($_GET)->links() }}
        <table class="table no-border table-hover table-striped mb-5">
            <thead>
            <tr>
                <th>オプションID</th>
                <th>オプション名</th>
                <th>オプション内容</th>
                <th>価格</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if ( isset($options) && count($options) > 0 )
                @foreach($options as $option)
                    <tr>
                        <td>{{ $option->id }}</td>
                        <td style="text-align: left">{{ $option->name }}</td>
                        <td>{{ $option->confirm }}</td>
                        <td>{{ number_format($option->price) }}</td>
                        <td>
                            <a href="{{ route('option.edit', $option->id) }}"
                               class="btn btn-primary">
                               <i class="fa fa-edit"> 編集</i>
                            </a>
                            <button class="btn btn-primary delete-btn delete-popup-btn" data-id="{{ $option->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">{{ trans('messages.no_record') }}</td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>

    {{ $options->appends($_GET)->links() }}


@stop