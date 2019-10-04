@php
    $params = [
      'delete_route' => 'billing.destroy'
    ];
@endphp
@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> </i> {{ trans('messages.billing') }}
    </h1>
@stop

@section('search')
    <div class="excel-btn-wrapper mb-5 text-right">
        <a href="{{ route('billing.excel.export', ['billing_month' => request('billing_month'), 'status' => request('status'), 'hospital_name' => request('hospital_name')]) }}" class="btn btn-primary btn-lg">請求一覧EXCELダウンロード</a>
    </div>

    <form method="get" role="form" action="{{ route('billing.index') }}">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="billing_month">請求月</label>
                    <select class="form-control" id="type" name="billing_month">
                        @foreach($selectBoxMonths as $selectBoxMonth)
                            <option value="{{ $selectBoxMonth }}"
                                    @if ( request('billing_month') && (request('billing_month') == $selectBoxMonth) )
                                        selected="selected"
                                    @else
                                        {{ ( empty(request('billing_month')) && $endedDate->format('Y-m') == $selectBoxMonth ) ? 'selected="selected"' : '' }}
                                    @endif
                            >{{ $selectBoxMonth }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="status">請求ステータス</label>

                    <select class="form-control" id="status" name="status">
                        <option value=""></option>
                        @foreach(\App\Enums\BillingStatus::toArray() as $key => $value)
                            <option
                                    value="{{ $value }}" {{ (request('status') == $value) ? "selected" : "" }}>{{ \App\Enums\BillingStatus::getDescription($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="hospital_name">医療機関名</label>
                    <input type="text" class="form-control" name="hospital_name" value="{{ request('hospital_name') }}">
                </div>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-search">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    検索
                </button>
            </div>
        </div>
    </form>
@stop


@section('table')

    <div class="row">

        <div class="col-sm-6">

            <div class="mt-5">
                @include('layouts.partials.pagination-label', ['paginator' => $billings])
            </div>

        </div>

        <div class="col-sm-6">

            {{ $billings->appends(request()->input())->links() }}

        </div>


    </div>


    <div class="table-responsive">
    <table id="example2" class="table table-bordered table-hover table-striped mb-5">
        <thead>
        <tr>
            <th>物件番号</th>
            <th>医療機関名</th>
            <th>請求ステータス</th>
            <th>プラン</th>
            <th>請求金額</th>
            <th>プラン金額（税抜金額）</th>
            <th>手数料合計金額（税抜金額）</th>
            <th>成果コース</th>
            <th colspan="4"></th>
        </tr>
        </thead>
        <tbody>
        @if ( isset($billings) && count($billings) > 0 )
            @foreach ($billings as $billing)
                <tr class="billing-id-{{ $billing->id }} status-{{ $billing->status }}">
                    <td>{{ $billing->hospital->contract_information->property_no ?? '' }}</td>
                    <td>{{ $billing->hospital->name }}</td>
                    <td>{{ \App\Enums\BillingStatus::getDescription($billing->status) }}</td>
                    <td>
                            {{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->plan_name }}
                    </td>
                    <td>
                       {{ number_format($billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->pluck('fee')->sum() + $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->monthly_contract_fee)}}円
                    </td>
                    <td>
                        {{ number_format($billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->monthly_contract_fee )}}円
                    </td>
                    <td>
                        {{ number_format($billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->pluck('tax_excluded_price')->sum()) }}円
                    </td>
                    <td>
                        {{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->fee_rate }}%
                    </td>
                    <td>
                        <a href="{{ route('billing.show', ['billing' => $billing]) }}" class="btn btn-primary">明細</a>
                    </td>
                    <td>
                            <a href="{{ route('billing.status.update', [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => 2, 'claim_check' => 'yes']) }}" class="btn @if( $billing->status != \App\Enums\BillingStatus::UNCONFIRMED ) btn-default @else btn-primary @endif"
                               @if( $billing->status != \App\Enums\BillingStatus::UNCONFIRMED ) style="pointer-events: none;" @endif
                            >請求確認</a>
                    </td>
                    <td>
                        <a href="{{ route('billing.status.update', [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => 4, 'claim_confirmation' => 'yes']) }}" class="btn @if( ($billing->status == \App\Enums\BillingStatus::CHECKING) || ($billing->status == \App\Enums\BillingStatus::CONFIRMED) ) btn-primary @else btn-default @endif"
                           @if( ($billing->status == \App\Enums\BillingStatus::CHECKING) || ($billing->status == \App\Enums\BillingStatus::CONFIRMED) )  style="pointer-events: unset;" @else style="pointer-events: none;" @endif
                        >請求確定</a>
                    </td>

                    <td>
                        <a href="{{ route('billing.status.update', [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => 2, 'undo_commit' => 'yes']) }}" class="btn @if( $billing->status == 4) btn-primary @else btn-default @endif"
                           @if( $billing->status == 4) style="pointer-events: unset;" @else style="pointer-events: none;" @endif
                        >確定取消</a>
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



@stop
