@php
use \App\Enums\HospitalEnums;
$params = [
    'delete_route' => 'hospital-staff.destroy',
    'create_route' => 'hospital-staff.create'
];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>医療機関</h1>
@stop

@section('search')

    <form action="{{ route('hospital.search') }}">

        <div class="std-container">
            <div class="row">

                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="s_text">医療機関名・ID</label>
                        <input type="text" class="form-control" autocomplete="off" name="s_text" id="s_text" value="{{ request('s_text') }}" />
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="status">状態</label>
                        <select name="status" id="status" class="form-control">
                            @foreach(\App\Enums\HospitalEnums::toArray() as $key)

                                <option
                                        value="{{ $key }}" {{ ( request('status') == $key) ? "selected" : "" }}>{{ \App\Enums\HospitalEnums::getDescription($key) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-center">
                    <button type="reset" class="btn btn-default">検索用にクリア</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        検索する
                    </button>
                </div>

            </div>
        </div>

    </form>

@stop


@section('table')


    @if ( isset($hospitals) && count($hospitals) > 0 )

        <div class="table-responsive">
        <table id="example2" class="table table-bordered table-hover mb-5 mt-5">
        <thead>
        <tr>
            <th>ID</th>
            <th>医療機関名</th>
            <th>所在地</th>
            <th>連絡先</th>
            <th>更新日</th>
            <th>状態</th>
            <th>施設</th>
            <th>編集</th>
            <th>削除</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($hospitals as $hospital)
            <tr class="
                    {{ ($hospital->status === \App\Enums\HospitalEnums::Private) ? 'private-row ' : '' }}
                    {{ ($hospital->status === \App\Enums\HospitalEnums::Public) ? 'public-row ' : '' }}
                    {{ ($hospital->status === \App\Enums\HospitalEnums::Delete) ? 'deleted-row ' : '' }}
                    ">
                <td>{{ $hospital->id }}</td>
                <td>{{ $hospital->name }}</td>
                <td>{{ $hospital->address1 }}</td>
                <td>{{ $hospital->tel }}</td>
                <td>{{ $hospital->created_at }}</td>
                <td>{{ \App\Enums\HospitalEnums::getDescription($hospital->status) }}</td>
                <td><a class="btn btn-primary" href="#">施設</a></td>
                <td>
                    @if ($hospital->status !== \App\Enums\HospitalEnums::Delete)
                    <a href="{{ route('hospital.edit', $hospital->id) }}"
                       class="btn btn-primary">編集</a>
                    @endif
                </td>
                <td>
                    @if ($hospital->status !== \App\Enums\HospitalEnums::Delete)
                    <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $hospital->id }}">
                        削除
                    </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>

    {{ $hospitals->links() }}

    @else

        @include('commons.alert', $alert = ['type' => 'danger', 'message' => trans('messages.no_record')])

    @endif
@stop


@push('js')
    <script src="{{ url('js/handlebars.js') }}"></script>
    <script src="{{ url('js/bootstrap3-typeahead.min.js') }}"></script>
    <script type="text/javascript">

        (function($){
            var route = "{{ route('hospital.search.text') }}";
            $('#s_text').typeahead({
                source:  function (term, process) {
                    return $.get(route, { term: term }, function (data) {
                        return process(data);
                    });
                },
                displayText: function(item){
                    return item.name + ' - ' + item.address1;
                },
                afterSelect: function(item) {
                    $('#s_text').val(item.name);
                }
            });

        })(jQuery);


    </script>

@endpush