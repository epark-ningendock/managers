@php
    use \App\Enums\HospitalEnums;
    $params = [
        'delete_route' => 'option.destroy'
    ];
@endphp
<style>
    .toCourseEdit{ height: 5em; overflow-y: hidden }
    .toCourseEdit.on{ height: auto }
    .readMore{ font-size: 90%; width: 100% }
</style>
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
                <th>提供コース名</th>
                <th>価格</th>
                <th style="width: 10em"></th>
            </tr>
            </thead>
            <tbody>
            @if ( isset($options) && count($options) > 0 )
                @foreach($options as $option)
                    <tr>
                        <td>{{ $option->id }}</td>
                        <td class="text-left">{{ $option->name }}</td>
                        <td class="text-left">
                            @php $i = 0; @endphp
                            @foreach($option->courses as $course)
                                @if($loop->first)<ul class="toCourseEdit">@endif
                                    <li><a href="/course/{{ $course->id }}/edit" target="_blank" title="{{ $course->name }}">{{ mb_strimwidth($course->name, 0, 70, "...") }}</a></li>
                                @if($loop->last)</ul>@endif
                                @php $i++; @endphp
                            @endforeach
                            @if($i > 3)<div class="text-center mt-4"><button class="btn btn-default btn-sm readMore">もっと見る</button></div>@endif
                        </td>
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

@section('js')
<script>
    $(function(){
        $('.readMore').on('click', function(e){
            $(this).parent('div').prev('ul').toggleClass('on');
            ($(this).parent('div').prev('ul').hasClass('on')) ? $(e.target).text('折りたたむ') : $(e.target).text('もっと見る')
        });
    });
</script>
@stop