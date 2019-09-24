@extends('layouts.list', $params = [])

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> </i> {{ trans('messages.billing') }}
    </h1>
@stop



@section('table')


    <ul class="billing-detail-list">
        <li>
            <small class="text-bold label-text">医療機関</small>
            <span class="value-text">{{ $billing->hospital->name }}</span>
        </li>
        <li>
            <small class="text-bold label-text">プラン名</small>
            <span class="value-text">{{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->plan_name ?? '' }}</span>
        </li>
        <li>
            <small class="text-bold label-text">月額契約料（税抜金額）</small>
            <span class="value-text">
                {{ number_format($billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->monthly_contract_fee )}}円
            </span>
        </li>
        <li>
            <small class="text-bold label-text">成果コース</small>
            <span class="value-text">
                {{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->fee_rate }}%
            </span>
        </li>
        <li>
            <small class="text-bold label-text">手数料合計金額（税抜価格）</small>
            <span class="value-text">
                {{ number_format($billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->pluck('fee')->sum()) }}円
            </span>
        </li>
    </ul>

    <p class="action-button-list text-center m-3 mb-5">

        <a href="{{ route('billing.status.update', [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => 2]) }}" class="btn btn-primary"
           @if( $billing->status == 1 ) style="pointer-events: none;" @endif
        >未確認</a>

        <a href="{{ route('billing.status.update', [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => 4]) }}" class="btn btn-primary"
           @if( $billing->status == 2  || $billing->status == 3) style="pointer-events: none;" @endif
        >請求確定</a>

        <a href="{{ route('billing.status.update', [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => 2]) }}" class="btn btn-primary"
           @if( $billing->status != 4) style="pointer-events: none;" @endif
        >確定取消</a>

    </p>


    <div class="table-responsive">

        <table id="example2" class="table table-bordered table-hover table-striped mb-5">

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
                        @if ( $reservation->status == 1 )
                            仮受付
                            @elseif ( $reservation->status == 2 )
                            受付確定
                                @elseif ( $reservation->status == 3 )
                            受診完了
                                    @elseif ( $reservation->status == 4 )
                            キャンセル

                            @endif

                    }}</td>
                    <td>@if ( isset($reservation->is_payment) && ( $reservation->is_payment == 1 ) ) 事前決済 @else 現地決済、@endif</td>
                    <td>{{ $reservation->course->name }}</td>
                    <td>{{ $reservation->tax_included_price ?? '' }}</td>
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
