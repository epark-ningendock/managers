@php
    use \App\Enums\HospitalEnums;
    $params = [
        'delete_route' => 'option.destroy',
        'create_route' => 'option.create'
    ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>オプション管理</h1>
@stop


@section('table')

    <p>一覧表示と編集オプション<br/>
                 オプションの並べ替えボタンを押すと、オプションの順序を並べ替えることができます。
    </p>


    <div class="action-btn-wrapper text-right m-4">

        <a href="{{ route('option.create') }}" class="btn btn-primary">
            オプション登録
        </a>

        <a href="{{ route('option.sort') }}" class="btn btn-primary">
            オプション並べ替え
        </a>

    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-5 mt-5">
            <thead>
            <tr>
                <th>オプションID</th>
                <th>オプション名</th>
                <th>オプション内容</th>
                <th>価格</th>
                <th>編集・削除</th>
            </tr>
            </thead>
            <tbody>
            @if ( isset($options) && count($options) > 0 )
                @foreach($options as $option)
                    <tr>
                        <td>{{ $option->id }}</td>
                        <td>{{ $option->name }}</td>
                        <td>{{ $option->confirm }}</td>
                        <td>{{ $option->price }}</td>
                        <td>
                            <a href="{{ route('option.edit', $option->id) }}"
                               class="btn btn-primary">編集</a>
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