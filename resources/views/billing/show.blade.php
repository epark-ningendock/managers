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
            <span class="value-text">{{ $billing->contractPlan->plan_name }}</span>
        </li>
        <li>
            <small class="text-bold label-text">月額契約料（税抜金額）</small>
            <span class="value-text">{{ $billing->contractPlan->monthly_contract_fee }}円</span>
        </li>
        <li>
            <small class="text-bold label-text">成果コース</small>
            <span class="value-text">{{ $billing->contractPlan->fee_rate }}%</span>
        </li>
        <li>
            <small class="text-bold label-text">手数料合計金額（税抜価格）</small>
            <span class="value-text">{{ $billing->hospital->reservations()->whereMonth('created_at', now()->month)->get()->pluck('fee')->sum() }}円</span>
        </li>
    </ul>

    <p class="action-button-list text-center m-3 mb-5">
        <a href="{{ route('billing.index') }}" class="btn btn-primary">{{ __('請求確認') }}</a>
        <a href="{{ route('billing.index') }}" class="btn btn-primary">{{ __('請求確定') }}</a>
        <a href="{{ route('billing.index') }}" class="btn btn-primary">{{ __('確定取消') }}</a>
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
            @if ( isset($billing->hospital->reservations) )
            @foreach( $billing->hospital->reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->completed_date->format('Y-m-d') }}</td>
                    <td>{{ $reservation->customer->family_name .' ' . $reservation->customer->first_name }}</td>
                    <td>{{ $reservation->channel ?? '' }}</td>
                    <td>{{ \App\Enums\ReservationStatus::getDescription($reservation->status) }}</td>
                    <td>{{ $reservation->is_payment ?? '' }}</td>
                    <td>{{ $reservation->course->name }}</td>
                    <td>{{ $reservation->tax_included_price ?? '' }}</td>
                    <td><span class="text-danger">Relationship is wrong</span></td>
                    <td>{{ $reservation->adjustment_price }}円</td>
                    <td>{{ $reservation->fee_rate }}%</td>
                    <td>{{ $reservation->fee }}円</td>
                    <td>{{ (isset($reservation->is_fee_hp_link) && ( $reservation->is_fee_hp_link == 1) ) ? __('無料HPリンク') : '' }}</td>
                </tr>
            @endforeach
                @else

                <tr><td colspan="13">{{ trans('messages.no_record') }}</td></tr>
            @endif
            </tbody>

        </table>

    </div>

@stop
