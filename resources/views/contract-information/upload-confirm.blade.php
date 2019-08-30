@extends('layouts.form')
@section('content_header')
  <h1>契約管理</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('contract.upload.store') }}" class="h-adr form-horizontal">
    {{ csrf_field() }}
    <div>
      <label class="mb-2">契約管理</label>
      <div class="field-group">
        <label class="mb-2">契約情報登録</label>
        <div class="field-group">
          <label class="mb-2">契約情報</label>
          <div class="field-group">
            <div class="form-group">
              <label class="col-md-4">物件番号</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('property_no')) text-red @endif">
                {{ $contract->property_no or '-' }}
                <input type="hidden" name="property_no" value="{{ $contract->property_no }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">ドックネットID</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('code')) text-red @endif">
                D{{ $contract->code }}
                <input type="hidden" name="code" value="{{ $contract->code }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">契約者名（フリガナ）</label>
              <div  class="col-md-8 @if(isset($contract->id) && $contract->isDirty('contractor_name_kana')) text-red @endif">
                {{ $contract->contractor_name_kana }}
                <input type="hidden" name="contractor_name_kana" value="{{ $contract->contractor_name_kana }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">契約者名</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('contractor_name')) text-red @endif">
                {{ $contract->contractor_name }}
                <input type="hidden" name="contractor_name" value="{{ $contract->contractor_name }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">申込日</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('application_date')) text-red @endif">
                {{ $contract->application_date->format('Y/m/d') }}
                <input type="hidden" name="application_date"
                       value="{{ $contract->application_date->format('Y/m/d') }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">課金開始日</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('billing_start_date')) text-red @endif">
                {{ $contract->billing_start_date->format('Y/m/d') }}
                <input type="hidden" name="billing_start_date"
                       value="{{ $contract->billing_start_date->format('Y/m/d') }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">解約日</label>
              <div
                  class="col-md-8 @if(isset($contract->id) && $contract->isDirty('cancellation_date')) text-red @endif">
                {{ isset($contract->cancellation_date) ? $contract->cancellation_date->format('Y/m/d') : '-' }}
                <input type="hidden" name="cancellation_date"
                       value="{{ isset($contract->cancellation_date) ? $contract->cancellation_date->format('Y/m/d') : '' }}"/>
              </div>
            </div>

          </div>
        </div>

        <div class="field-group mt-4">
          <label class="mb-2">契約者情報</label>
          <div class="field-group">

            <div class="form-group">
              <label class="col-md-4">代表者名（フリガナ）</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('representative_name_kana')) text-red @endif">
                {{ $contract->representative_name_kana }}
                <input type="hidden" name="representative_name_kana" value="{{ $contract->representative_name_kana }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">代表者</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('representative_name')) text-red @endif">
                {{ $contract->representative_name }}
                <input type="hidden" name="representative_name" value="{{ $contract->representative_name }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">屋号</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->hospital->isDirty('name')) text-red @endif">
                {{ isset($contract->id) ? $contract->hospital->name : $contract_hospital_name }}
                <input type="hidden" name="hospital_name" value="{{ isset($contract->id) ? $contract->hospital->name : $contract->hospital_name }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">屋号(フリガナ)</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->hospital->isDirty('kana')) text-red @endif">
                {{ isset($contract->id) ? $contract->hospital->kana : $contract_hospital_name_kana }}
                <input type="hidden" name="hospital_name_kana" value="{{ isset($contract->id) ? $contract->hospital->kana : $contract->hospital_name_kana }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">郵便番号</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('postcode')) text-red @endif">
                {{ $contract->postcode or '-' }}
                <input type="hidden" name="postcode" value="{{ $contract->postcode }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">住所</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('address')) text-red @endif">
                {{ $contract->address or '-' }}
                <input type="hidden" name="address" value="{{ $contract->address }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">電話番号</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('tel')) text-red @endif">
                {{ $contract->tel or '-' }}
                <input type="hidden" name="tel" value="{{ $contract->tel }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">FAX番号</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('fax')) text-red @endif">
                {{ $contract->fax or '-' }}
                <input type="hidden" name="fax" value="{{ $contract->fax }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">メールアドレス</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('email')) text-red @endif">
                {{ $contract->email or '-' }}
                <input type="hidden" name="email" value="{{ $contract->email }}"/>
              </div>
            </div>

          </div>
        </div>


        <div class="field-group mt-4">
          <label class="mb-2">プラン</label>
          <div class="field-group">

            <div class="form-group">
              <label class="col-md-4">プランコード</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->contract_plan_id != $contract_plan->id) text-red @endif">
                {{ $contract_plan->id }}
                <input type="hidden" name="plan_code" value="{{ $contract_plan->plan_code }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">プラン名</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->contract_plan_id != $contract_plan->id) text-red @endif">
                {{ $contract_plan->plan_name or '-' }}
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">サービス開始日</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('service_start_date')) text-red @endif">
                {{ $contract->service_start_date->format('Y/m/d') }}
                <input type="hidden" name="service_start_date" value="{{ $contract->service_start_date->format('Y/m/d') }}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4">サービス終了日</label>
              <div class="col-md-8 @if(isset($contract->id) && $contract->isDirty('service_end_date')) text-red @endif">
                {{ isset($contract->service_end_date) ? $contract->service_end_date->format('Y/m/d') : '-' }}
                <input type="hidden" name="service_end_date" value="{{ isset($contract->service_end_date) ? $contract->service_end_date->format('Y/m/d') : '' }}"/>
              </div>
            </div>

          </div>
        </div>

      </div>
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
