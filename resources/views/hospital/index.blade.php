@php
  use App\Enums\HospitalEnums;
  use App\Prefecture;
  use App\DistrictCode;
  $params = [
    'delete_route' => 'hospital.destroy'
  ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>
    <i class="fa fa-hospital-o"> 医療機関管理</i>
  </h1>
@stop

@section('search')
  <form action="{{ route('hospital.search') }}">
    <div class="std-container">
      <div class="row">
        <div class="col-sm-9">
          <div class="form-group">
            <label for="s_text">医療機関名・顧客番号</label>
            <input type="text" class="form-control" autocomplete="off" name="s_text" id="s_text"
                 value="{{ request('s_text') }}"/>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label for="status">状態</label>
            <select name="status" id="status" class="form-control">
              @foreach(HospitalEnums::toArray() as $key => $value)
                <option value="{{ $value }}" {{ ( request('status') == $value) ? "selected" : "" }}>
                  {{ HospitalEnums::getDescription($value) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="text-center">
          @if($request->path() == 'hospital/search')
            <a class="btn btn-default" href="{{ route('hospital.index') }}">
              検索条件クリア
            </a>
          @else
            <button type="reset" class="btn btn-default">検索条件クリア</button>
          @endif
          <button type="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            検索
          </button>
        </div>
      </div>
    </div>
  </form>
@stop

@section('table')
  <p class="table-responsive">
    @include('layouts.partials.pagination-label', ['paginator' => $hospitals])
    <table id="example2" class="table table-striped table-hover no-border vertical-middle mb-5">
      <thead>
      <tr>
        <th>ID</th>
        <th>顧客番号</th>
        <th>医療機関名</th>
        <th>所在地</th>
        <th>連絡先</th>
        <th>状態</th>
        @if (Auth::user()->staff_auth->is_hospital === 3)
          <th></th>
        @endif
      </tr>
      </thead>
      <tbody>
      @if ( isset($hospitals) && count($hospitals) > 0 )
        @foreach ($hospitals as $hospital)
          <tr class="
          {{ ($hospital->status === HospitalEnums::PRIVATE) ? 'light-gray ' : '' }}
          {{ ($hospital->status === HospitalEnums::PUBLIC) ? '' : '' }}
          {{ ($hospital->status === HospitalEnums::DELETE) ? 'dark-gray' : '' }}
              ">
            <td>{{ $hospital->id }}</td>
            <td>{{ $hospital->contract_information->customer_no }}</td>
            <td style="text-align: left">{{ $hospital->name }}</td>
            @if (!$hospital->prefecture_id && !$hospital->district_code_id)
              <td></td>
            @elseif (DistrictCode::find($hospital->district_code_id))
              <td style="text-align: left">{{ Prefecture::find($hospital->prefecture_id)->name . DistrictCode::find($hospital->district_code_id)->name . $hospital->address1 }}</td>
            @else
              <td style="text-align: left">{{ Prefecture::find($hospital->prefecture_id)->name ?? '' . $hospital->address1 ?? '' }}</td>
            @endif
            <td>{{ $hospital->tel }}</td>
            <td>{{ HospitalEnums::getDescription($hospital->status) }}</td>
            @if (Auth::user()->staff_auth->is_hospital === 3)
              <td>
                <a class="btn btn-primary insert-hospital-id-popup-btn" data-id="{{ $hospital->id }}">
                  <i><span class="fa fa-pencil"></span></i>
                </a>
                <form class="hide" id="select-hospital-form" method="GET"  action="{{ route('hospital.select', ['hospital->id' => ':id']) }}">
                  {{ csrf_field() }}
                </form>
                @if ($hospital->status !== HospitalEnums::DELETE)
                  <a href="{{ route('hospital.edit', ['id' => $hospital->id]) }}"
                     class="btn btn-primary">
                    <i class="fa fa-edit"> 編集</i>
                  </a>
                @endif
                @if ($hospital->status !== HospitalEnums::DELETE)
                  <button class="btn  btn-primary delete-btn delete-popup-btn"
                          data-id="{{ $hospital->id }}">
                    <i class="fa fa-trash"></i>
                  </button>
                @endif
              </td>
            @endif
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
<style>
  tr.dark-gray td {
    background-color: darkgray;
  }
  tr.light-gray td {
    background-color: lightgray;
  }
</style>

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
