@extends('layouts.form')
@section('content_header')
  <h1>契約管理</h1>
@stop

@section('form')
  <form method="POST" action="{{ '/hospital/contract-store' }}" class="h-adr form-horizontal">
    {{ csrf_field() }}
    <label class="mb-2">契約管理</label>
    <br/>
    <label class="mb-2 ml-5">契約情報登録</label>
    <div class="staff-table-responsive table-responsive mt-3">
      <table id="example2" class="table table-bordered table-hover table-striped no-border">
        <thead>
        <tr>
          <th></th>
          <th>{{ trans('messages.property_no') }}</th>
          <th>{{ trans('messages.customer_no') }}</th>
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
        @foreach ($contracts as $i => $contract)
          <tr>
            <td>{{ isset($contract->id) ? '更新' : '新規' }}</td>
            <td class="@if(isset($contract->id) && $contract->isDirty('property_no')) text-red @endif">
              {{ $contract->property_no }}
              <input type="hidden" name="contracts[{{$i}}][property_no]" value="{{ $contract->property_no }}">
              <input type="hidden" name="contracts[{{$i}}][code]" value="{{ $contract->code }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('customer_no')) text-red @endif">
              {{ $contract->customer_no }}
              <input type="hidden" name="contracts[{{$i}}][customer_no]" value="{{ $contract->customer_no }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('contractor_name')) text-red @endif">
              {{ $contract->contractor_name }}
              <input type="hidden" name="contracts[{{$i}}][contractor_name]" value="{{ $contract->contractor_name }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('contractor_name_kana')) text-red @endif">
              {{ $contract->contractor_name_kana }}
              <input type="hidden" name="contracts[{{$i}}][contractor_name_kana]" value="{{ $contract->contractor_name_kana }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('application_date')) text-red @endif">
              {{ $contract->application_date->format('Y/m/d') }}
              <input type="hidden" name="contracts[{{$i}}][application_date]" value="{{ $contract->application_date->format('Ymd') }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('billing_start_date')) text-red @endif">
              {{ isset($contract->billing_start_date) ? $contract->billing_start_date->format('Y/m/d') : '-' }}
              <input type="hidden" name="contracts[{{$i}}][billing_start_date]" value="{{ isset($contract->billing_start_date) ? $contract->billing_start_date->format('Ymd') : '' }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('cancellation_date')) text-red @endif">
              {{ isset($contract->cancellation_date) ? $contract->cancellation_date->format('Y/m/d') : '-' }}
              <input type="hidden" name="contracts[{{$i}}][cancellation_date]"
                     value="{{ isset($contract->cancellation_date) ? $contract->cancellation_date->format('Ymd') : ''}}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('representative_name')) text-red @endif">
              {{ $contract->representative_name }}
              <input type="hidden" name="contracts[{{$i}}][representative_name]" value="{{ $contract->representative_name }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('representative_name_kana')) text-red @endif">
              {{ $contract->representative_name_kana }}
              <input type="hidden" name="contracts[{{$i}}][representative_name_kana]" value="{{ $contract->representative_name_kana }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->hospital->isDirty('name')) text-red @endif">
              {{ $contract->hospital->name }}
              <input type="hidden" name="contracts[{{$i}}][hospital_name]" value="{{ $contract->hospital->name }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('postcode')) text-red @endif">
              {{ $contract->postcode }}
              <input type="hidden" name="contracts[{{$i}}][postcode]" value="{{ $contract->postcode }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('address')) text-red @endif">
              {{ $contract->address }}
              <input type="hidden" name="contracts[{{$i}}][address]" value="{{ $contract->address }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('tel')) text-red @endif">
              {{ $contract->tel }}
              <input type="hidden" name="contracts[{{$i}}][tel]" value="{{ $contract->tel }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('fax')) text-red @endif">
              {{ $contract->fax }}
              <input type="hidden" name="contracts[{{$i}}][fax]" value="{{ $contract->fax }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('email')) text-red @endif">
              {{ $contract->email }}
              <input type="hidden" name="contracts[{{$i}}][email]" value="{{ $contract->email }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->is_plan_change) text-red @endif">
              {{ $contract->contract_plan->plan_code }}
              <input type="hidden" name="contracts[{{$i}}][plan_code]" value="{{ $contract->contract_plan->plan_code }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->is_plan_change) text-red @endif">
              {{ $contract->contract_plan->plan_name }}
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('service_start_date')) text-red @endif">
              {{ isset($contract->service_start_date) ? $contract->service_start_date->format('Y/m/d') : '-' }}
              <input type="hidden" name="contracts[{{$i}}][service_start_date]" value="{{ isset($contract->service_start_date) ? $contract->service_start_date->format('Ymd') : '' }}">
            </td>
            <td class="@if(isset($contract->id) && $contract->isDirty('service_end_date')) text-red @endif">
              {{ isset($contract->service_end_date) ?  $contract->service_end_date->format('Y/m/d') : '-' }}
              <input type="hidden" name="contracts[{{$i}}][service_end_date]" value="{{ isset($contract->service_end_date) ? $contract->service_end_date->format('Ymd') : '' }}">
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <div class="box-footer mt-5">
      <a href="{{ route('contract.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">作成</button>
    </div>

  </form>

@stop

@push('css')
  <style>
    .form-group {
      margin-bottom: 5px;
    }

    .field-group {
      padding-left: 25px;
    }
  </style>
@endpush