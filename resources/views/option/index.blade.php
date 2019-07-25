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
        <i class="fa fa-book"> オプション管理</i>
    </h1>
@stop

@section('search')
    <p>一覧表示と編集オプション<br/>
                 オプションの並べ替えボタンを押すと、オプションの順序を並べ替えることができます。
    </p>


    <div class="action-btn-wrapper text-right m-4">

        <a href="{{ route('option.create') }}" class="btn btn-success">
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
        <table class="table table-bordered table-hover table-striped mb-5">
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
                               class="btn btn-primary">
                               <i class="fa fa-edit text-bold"> 編集</i>
                            </a>
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