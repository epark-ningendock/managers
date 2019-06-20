@php
    use \App\Enums\HospitalEnums;
    $params = [
        'delete_route' => 'hospital.destroy',
        'create_route' => 'hospital.create'
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
                        <input type="text" class="form-control" autocomplete="off" name="s_text" id="s_text"
                               value="{{ request('s_text') }}"/>
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

@section('button')
@include('hospital.partials.record-management-modal-box')
  <div class="pull-right">
    <a class="btn btn-success btn-create" href="{{ route('hospital.contractInfo') }}">新規作成</a>
  </div>
@stop




@section('table')

    <div class="table-responsive">
        <table id="example2" class="table table-bordered table-hover mb-5 mt-5">
            <thead>
            <tr>
                <th>ID</th>
                <th>医療機関名</th>
                <th>所在地</th>
                <th>連絡先</th>
                <th>状態</th>
                <th>施設</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
            </thead>
            <tbody>
            @if ( isset($hospitals) && count($hospitals) > 0 )
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
                                <button class="btn btn-danger delete-btn delete-popup-btn"
                                        data-id="{{ $hospital->id }}">
                                    削除
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">{{ trans('messages.no_record') }}</td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>

    {{ $hospitals->links() }}

@stop


@push('js')
    <script src="{{ url('js/handlebars.js') }}"></script>
    <script src="{{ url('js/bootstrap3-typeahead.min.js') }}"></script>
    <script type="text/javascript">

        (function ($) {
            var route = "{{ route('hospital.search.text') }}";
            $('#s_text').typeahead({
                source: function (term, process) {
                    return $.get(route, {term: term}, function (data) {
                        return process(data);
                    });
                },
                displayText: function (item) {
                    return item.name + ' - ' + item.address1;
                },
                afterSelect: function (item) {
                    $('#s_text').val(item.name);
                }
            });

        })(jQuery);


    </script>

@endpush