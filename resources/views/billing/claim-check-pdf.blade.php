<!doctype html>
<html lang="en">
@php
    use App\Enums\ReservationStatus;
@endphp
<head>

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>請求確認PDF</title>
    <style>
        body *{
            font-family: ipag;

        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 3px;
            vertical-align: top;
            border-top: 1px solid #eceeef;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #eceeef;
        }

        .table tbody + tbody {
            border-top: 2px solid #eceeef;
        }

        .table .table {
            background-color: #fff;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #eceeef;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #eceeef;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-active,
        .table-active > th,
        .table-active > td {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover > td,
        .table-hover .table-active:hover > th {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-success,
        .table-success > th,
        .table-success > td {
            background-color: #dff0d8;
        }

        .table-hover .table-success:hover {
            background-color: #d0e9c6;
        }

        .table-hover .table-success:hover > td,
        .table-hover .table-success:hover > th {
            background-color: #d0e9c6;
        }

        .table-info,
        .table-info > th,
        .table-info > td {
            background-color: #d9edf7;
        }

        .table-hover .table-info:hover {
            background-color: #c4e3f3;
        }

        .table-hover .table-info:hover > td,
        .table-hover .table-info:hover > th {
            background-color: #c4e3f3;
        }

        .table-warning,
        .table-warning > th,
        .table-warning > td {
            background-color: #fcf8e3;
        }

        .table-hover .table-warning:hover {
            background-color: #faf2cc;
        }

        .table-hover .table-warning:hover > td,
        .table-hover .table-warning:hover > th {
            background-color: #faf2cc;
        }

        .table-danger,
        .table-danger > th,
        .table-danger > td {
            background-color: #f2dede;
        }

        .table-hover .table-danger:hover {
            background-color: #ebcccc;
        }

        .table-hover .table-danger:hover > td,
        .table-hover .table-danger:hover > th {
            background-color: #ebcccc;
        }

        .thead-inverse th {
            color: #fff;
            background-color: #292b2c;
        }

        .thead-default th {
            color: #464a4c;
            background-color: #eceeef;
        }

        .table-inverse {
            color: #fff;
            background-color: #292b2c;
        }

        .table-inverse th,
        .table-inverse td,
        .table-inverse thead th {
            border-color: #fff;
        }

        .table-inverse.table-bordered {
            border: 0;
        }

        .table-responsive.table-bordered {
            border: 0;
        }
    </style>
</head>
<body>

<ul class="billing-detail-list">
    <li>
        <small class="text-bold label-text">{{ __('医療機関') }}</small>
        <span class="value-text">{{ $billing->hospital->name }}</span>
    </li>
    <li>
        <small class="text-bold label-text">プラン名</small>
        <span class="value-text">{{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->plan_name ?? '' }}</span>
    </li>
    <li>
        <small class="text-bold label-text">プラン金額</small>
        <span class="value-text">
               {{ number_format($billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->monthly_contract_fee  + $billing->adjustment_price )}}円
            </span>
    </li>
    @if (!empty($billing->hospital->hospital_option_plans) )
        @foreach( $billing->hospital->hospital_option_plans as $hospital_option_plan)
            <li>
                <small class="text-bold label-text">{{ $hospital_option_plan->option_plan->option_plan_name }}</small>
                <span class="value-text">
                {{ number_format($hospital_option_plan->price) }} 円
            </span>
            </li>
        @endforeach
    @endif
    <li>
        <small class="text-bold label-text">成果コース</small>
        <span class="value-text">
                {{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->fee_rate }}%
            </span>
    </li>
    <li>
        <small class="text-bold label-text">手数料合計金</small>
        <span class="value-text">
                {{ number_format($billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->pluck('fee')->sum()) }}円
            </span>
    </li>
</ul>

<br><br><br>

<div class="table-responsive">

    <table id="example2" class="table table-bordered table-hover table-striped mb-5 pdftable">

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
                    <td>{{ $reservation->reservation_date->format('Y-m-d') }}</td>
                    <td>{{ $reservation->customer->family_name . ' ' . $reservation->customer->first_name }}</td>
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
                    <td>{{ number_format($reservation->tax_included_price) . '円' ?? '' }}</td>
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

    <br>
    @if ($billing->hospital->payment_type == 0)
        【振込先】 銀行名：りそな銀行　　　　　　　　<br>
        　　　　　　支店名：市ヶ谷支店　　　　　 　　<br>
        　　　　　　預金種目：普通預金　　　　 　　　<br>
        　　　　　　口座番号：1659966　　　　　　 　<br>
        　　　　　　口座名義：エンパワーヘルスケア(カ<br>
    @elseif($billing->hospital->payment_type == 1)
        【引落名義】 エンパワーヘルスケア 株式会社　　<br>
        　　　　　　 エンパワーヘルスケア（カ　　 　　<br>
    @endif

</div>


</body>
</html>
