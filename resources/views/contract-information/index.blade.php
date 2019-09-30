@extends('layouts.list', [])

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>
    <i class="fa fa-users">契約管理</i>
  </h1>
@stop

<!-- search section -->
@section('search')
  <form role="form">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="name">キーワード</label>
          <input type="text" class="form-control" id="search_text" name="search_text" value="{{ $search_text or '' }}">
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="status">状態</label>
          <select class="form-control" id="status" name="status">
            <option></option>
            <option value="UNDER_CONTRACT" {{ (!isset($status) || $status == 'UNDER_CONTRACT') ? "selected" : "" }}>契約中</option>
            <option value="CANCELLED" {{ (isset($status) && $status == 'CANCELLED') ? "selected" : "" }}>解約済</option>
          </select>
        </div>
      </div>
      <div class="col-md-3 mt-4 pt-2">
        <button type="submit" class="btn btn-primary">
          <i class="glyphicon glyphicon-search"></i> 検索
        </button>
        <button type="button" class="btn btn-success ml-1" id="btn-upload">
          TSVアップロード
        </button>
      </div>
    </div>
  </form>
@stop

@section('table')
  <div class="staff-table-responsive table-responsive mt-3">
    @include('layouts.partials.pagination-label', ['paginator' => $contract_informations])
    <table id="example2" class="table table-bordered table-hover table-striped no-border staff-table">
      <thead>
      <tr>
        <th>
          <a href="{{ route('contract.index', queryForSorting('property_no_sorting')) }}">
            {{ trans('messages.property_no') }}
            <i class="fa @if(request()->input('property_no_sorting') == 'asc')  fa-sort-asc @elseif(request()->input('property_no_sorting') == 'desc') fa-sort-desc @else fa-sort @endif"></i>
          </a>
        </th>
        <th>{{ trans('messages.contractor_name')  }}</th>
        <th>{{ trans('messages.contractor_name_kana') }}</th>
        <th>{{ trans('messages.application_date') }}</th>
        <th>{{ trans('messages.billing_start_date') }}</th>
        <th>{{ trans('messages.cancellation_date') }}</th>
        <th>{{ trans('messages.representative_name') }}</th>
        <th>{{ trans('messages.representative_name_kana') }}</th>
        <th>{{ trans('messages.hospital_name') }}</th>
        <th>{{ trans('messages.postcode') }}</th>
        <th>{{ trans('messages.address') }}</th>
        <th>{{ trans('messages.phone_number') }}</th>
        <th>{{ trans('messages.fax_no') }}</th>
        <th>{{ trans('messages.email') }}</th>
        <th>{{ trans('messages.plan_code') }}</th>
        <th>{{ trans('messages.plan_name') }}</th>
        <th>{{ trans('messages.service_start_date') }}</th>
        <th>{{ trans('messages.service_end_date') }}</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($contract_informations as $contract_information)
        <tr>
          <td>{{ $contract_information->property_no }}</td>
          <td>{{ $contract_information->contractor_name }}</td>
          <td>{{ $contract_information->contractor_name_kana }}</td>
          <td>{{ $contract_information->application_date->format('Y/m/d') }}</td>
          <td>{{ $contract_information->billing_start_date->format('Y/m/d') }}</td>
          <td>{{ isset($contract_information->cancellation_date) ? $contract_information->cancellation_date->format('Y/m/d') : '-' }}</td>
          <td>{{ $contract_information->representative_name }}</td>
          <td>{{ $contract_information->representative_name_kana }}</td>
          <td>{{ $contract_information->hospital->name or '-' }}</td>
          <td>{{ $contract_information->postcode }}</td>
          <td>{{ $contract_information->address }}</td>
          <td>{{ $contract_information->tel }}</td>
          <td>{{ $contract_information->fax }}</td>
          <td>{{ $contract_information->email }}</td>
          <td>{{ $contract_information->contract_plan->plan_code }}</td>
          <td>{{ $contract_information->contract_plan->plan_name or '-' }}</td>
          <td>{{ isset($contract_information->service_start_date) ? $contract_information->service_start_date->format('Y/m/d') : '-' }}</td>
          <td>{{ isset($contract_information->service_end_date) ? $contract_information->service_end_date->format('Y/m/d') : '-' }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <div class="paginate-box">
    {{ $contract_informations->appends(request()->except('page'))->links() }}
  </div>
  <form action="{{ route('contract.upload') }}" method="post" id="upload-form" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="file" name="file" id="file" style="width: 0px; height: 0px;" accept=".csv,.tsv"/>
  </form>
@stop

@push('js')
  <script type="text/javascript">

      (function ($) {

          /* ---------------------------------------------------
           TSV file upload trigger
          -----------------------------------------------------*/
          (function() {
              $('#btn-upload').click(function () {
                  $('#file').trigger('click');
              });
          })();

          /* ---------------------------------------------------
           TSV file upload
          -----------------------------------------------------*/
          (function() {
              $('#file').change(function () {
                  $('#upload-form').submit();
              });
          })();
      })(jQuery);


  </script>

@endpush