@php
    use App\TaxClass;
    use App\Enums\ReservationStatus;
    use App\Enums\BillingStatus;
@endphp

@extends('layouts.list', $params = [])

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> </i> {{ trans('messages.billing') }}-
        <span> {{ $billing->hospital->name }}</span>
    </h1>
@stop

@section('billing_info')
    <form class="box box-primary" method="post" role="form" action="{{ route('billing.update') }}">
        <input type="hidden" name="billing_id" value="{{$billing->id}}">
        <div class="box-header with-border">
            <div class="box-tools" data-widget="collapse">
                <button type="button" class="btn btn-sm">
                    <i class="fa fa-minus"></i></button>
            </div>
            <h1 class="box-title">医療機関 {{ $billing->hospital->name }}</h1>
        </div>

        <div id="billing-info" class="form-entry">
            <div class="box-body">
                <div class="form-group ">
                    <p>
                        <span class="text-bold label-text">プラン名</span>
                        {{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->plan_name ?? '' }}　
                    </p>
                </div>
                <div class="form-group ">
                    <span class="text-bold label-text">プラン金額</span>
                    {{ number_format($billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->monthly_contract_fee ) }}円　
                    <span class="text-bold label-text">調整金額</span>
                    <input type="text" id="billing_adjustment_price" name="adjustment_price" value="{{$billing->adjustment_price}}">
                    <span class="text-bold label-text">プラン請求金額</span>　
                    {{number_format($billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->monthly_contract_fee + $billing->adjustment_price)}}円
                <div class="form-group ">
                    <p>
                        <span class="text-bold label-text">成果コース</span>
                        {{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->fee_rate }}%
                    </p>
                </div>
                @if (!empty($billing->hospital->hospitalOptionPlan($billing->id, $endedDate)))
                    <div class="form-group ">
                        <span class="text-bold label-text">オプションプラン</span>
                        @foreach($billing->hospital->hospitalOptionPlan($billing->id, $endedDate) as $hospital_plan)
                            <p>
                               　{{ $hospital_plan->option_plan->option_plan_name ?? '' }}　
                               {{ number_format($hospital_plan->option_plan->option_plan_price) }}円　
                                オプションプラン調整金額
                                <input type="text" id="optionplanadjustmentprice_{{$hospital_plan->option_plan_id}}" name="optionplanadjustmentprice_{{$hospital_plan->option_plan_id}}" value="{{$hospital_plan->billing_option_plans->adjustoment_price}}">　
                                オプションプラン請求金額
                                {{number_format($hospital_plan->option_plan->option_plan_price + $hospital_plan->billing_option_plans->adjustoment_price)}}円
                            </p>

                        @endforeach
                    </div>
                @endif
                @if ($billing->hospital->hplink_contract_type == \App\Enums\HplinkContractType::MONTHLY_SUBSCRIPTION)
                    <div class="form-group ">
                        <span class="text-bold label-text">HPリンク月額</span>
                        {{ number_format($billing->hospital->hplink_price) }}円
                    </div>
                @endif

                <div class="form-group ">
                    <p><span class="text-bold label-text">手数料合計金額</span>{{ number_format($billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->pluck('fee')->sum()) }}円</p>
                </div>

                <div class="form-group ">
                    <p><span class="text-bold label-text">請求金額合計（税抜金額）</span>{{ number_format($billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->monthly_contract_fee
                    + $billing->adjustment_price
                    + $billing->hospital->hpLinkMonthPrice()
                                + $billing->hospital->hospitalOptionPlanPrice($billing->id, $endedDate)
                    + $billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->pluck('fee')->sum()) }}円
                        ( {{ number_format(floor($billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->monthly_contract_fee
                    + $billing->adjustment_price
                    + $billing->hospital->hpLinkMonthPrice()
                                + $billing->hospital->hospitalOptionPlanPrice($billing->id, $endedDate)
                    + $billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->pluck('fee')->sum()) * 0.9) }}円 )</p>
                </div>
                    <div class="form-group ">
                        <input type="submit" class="btn btn-primary" value="更新">
                    </div>
            </div>
        </div>
    </div>
    </form>
@stop


@section('table')

    <p class="action-button-list text-center m-3 mb-5">

        @if ( session('hospital_id') )

        <a href="{{ route('billing.status.update', array_merge( request()->all(), [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => BillingStatus::CONFIRMED, 'claim_check' => 'yes'] )) }}"
            class="btn @if( $billing->status != BillingStatus::CHECKING ) btn-default @else btn-primary @endif"
          @if( $billing->status != BillingStatus::CHECKING ) style="pointer-events: none;" @endif
        >請求確認</a>

        @else

        <a href="{{ route('billing.status.update', array_merge( request()->all(), [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => BillingStatus::CHECKING, 'claim_check' => 'yes'] )) }}"
            class="btn @if( $billing->status != BillingStatus::UNCONFIRMED ) btn-default @else btn-primary @endif"
          @if( $billing->status != BillingStatus::UNCONFIRMED ) style="pointer-events: none;" @endif
        >請求確認</a>

        <a href="{{ route('billing.status.update', array_merge(request()->all(), [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => BillingStatus::CONFIRM, 'claim_confirmation' => 'yes'])) }}"
            class="btn @if( ($billing->status == BillingStatus::CHECKING) || ($billing->status == BillingStatus::CONFIRMED) ) btn-primary @else btn-default @endif"
           @if( ($billing->status == BillingStatus::CHECKING) || ($billing->status == BillingStatus::CONFIRMED) )  style="pointer-events: unset;" @else style="pointer-events: none;" @endif
        >請求確定</a>

        <a href="{{ route('billing.status.update', array_merge(request()->all(), [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => BillingStatus::CHECKING, 'undo_commit' => 'yes'])) }}"
            class="btn @if( $billing->status == BillingStatus::CONFIRM) btn-primary @else btn-default @endif"
           @if( $billing->status == BillingStatus::CONFIRM) style="pointer-events: unset;" @else style="pointer-events: none;" @endif
        >確定取消</a>

        @endif

    </p>


    <div class="table-responsive">

        <table id="example2" class="table no-border table-hover table-striped mb-5">

            <thead>
            <tr>
                <th>予約番号</th>
                <th>受診日</th>
                <th>受診者名</th>
                <th>媒体</th>
                <th>ステータス</th>
                <th>決済方法</th>
                <th>コース</th>
                <th>コース金額</th>
                <th>オプション金額</th>
                <th>調整額</th>
                <th>手数料率</th>
                <th>手数料（税込）</th>
                <th>無料HPリンク</th>
            </tr>
            </thead>
            <tbody>
            @if (! $billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->isEmpty() )
            @foreach( $billing->hospital->reservationByCompletedDate($startedDate, $endedDate) as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->completed_date->format('Y-m-d') }}</td>
                    <td>{{ $reservation->customer->family_name .' ' . $reservation->customer->first_name }}</td>
                    <td>{{ ( isset($reservation->channel) && ( $reservation->channel == 1)) ? 'WEB' : 'TEL' }}</td>
                    <td>
                        @if ( $reservation->reservation_status->is(ReservationStatus::PENDING) )
                            仮受付
                        @elseif ( $reservation->reservation_status->is(ReservationStatus::RECEPTION_COMPLETED) )
                            受付確定
                        @elseif ( $reservation->reservation_status->is(ReservationStatus::COMPLETED) )
                            受診完了
                        @elseif ( $reservation->reservation_status->is(ReservationStatus::CANCELLED) )
                            キャンセル
                        @endif
                    </td>
                    <td>@if ( isset($reservation->is_payment) && ( $reservation->is_payment == 1 ) ) 事前決済 @else 現地決済 @endif</td>
                    <td>{{ $reservation->course->name }}</td>
                    <td>{{ ( isset($reservation->tax_included_price) ) ? number_format($reservation->tax_included_price)  . '円' : '' }}</td>
                    <td>{{ ( $reservation->reservation_options->pluck('option_price')->sum() ) ? number_format($reservation->reservation_options->pluck('option_price')->sum()) . '円' : '' }}</td>
                    <td>{{ (isset($reservation->adjustment_price) ) ? number_format($reservation->adjustment_price) . '円' : '' }}</td>
                    <td>{{ $reservation->fee_rate }}%</td>
                    <td>{{ ( isset($reservation->fee) ) ? number_format($reservation->fee) . '円' : '' }}</td>
                    <td>{{ (isset($reservation->is_free_hp_link) && ( $reservation->is_free_hp_link == 1) ) ? '無料HPリンク' : '' }}</td>
                </tr>
            @endforeach
                @else

                <tr><td colspan="13">{{ trans('messages.no_record') }}</td></tr>
            @endif
            </tbody>

        </table>

    </div>

@stop
