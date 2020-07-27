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
        .span_center {
            font-size: 2ex;
            font-weight: bold;
            display:block;
            text-align:center;
        }
        .span_center_date {
            display:block;
            text-align:center;
        }
        .span_right {
            display:block;
            text-align:right;/*右寄せ*/
        }
        .span_right_comp {
            font-size: 2ex;
            font-weight: bold;
            display:block;
            text-align:right;/*右寄せ*/
        }
        .plan_head {
            background-color: #d3d3d3;
        }
    </style>
</head>
<body>
<div class="table-responsive">
<span class="span_center">請求内訳明細書</span><span class="span_right">発効日：{{$today_date}}</span><br/>
<span class="span_center_date">（{{$period}}）</span><br/>

<span style="border-bottom: solid 2px;">{{ $billing->hospital->contract_information->contractor_name }} 御中 </span>
<span class="span_right_comp">株式会社EPARK人間ドック　　　　　　 　　　</span>
<span class="span_right">東京都港区芝大門1-2-13　MSC芝大門ビル6F　　　　</span>　
　　　　　ご請求金額<span style="border-bottom: solid 1px;">　¥{{number_format($total_price)}}</span>（税込）
<span class="span_right">TEL：0120-201-637　FAX：03-4560-769　 　　　</span><br/><br/>

各プラン・サービス別内訳明細
<table border="1" style="border-collapse: collapse">
    <tr class="plan_head">
        <th width="35%">プラン・サービス名</th>
        <th width="10%">項目</th>
        <th width="5%">数量</th>
        <th width="10%">単価</th>
        <th width="10%">金額(税込)</th>
        <th width="30%">備考</th>
    </tr>
    <tr>
        <td>
            {{ $billing->hospital->hospitalPlanByDate($endedDate)->contractPlan->plan_name ?? '' }}
        </td>
        <td>
            月額費用
        </td>
        <td align="center">
            1
        </td>
        <td align="right">
            ¥{{ number_format($billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->monthly_contract_fee  + $billing->adjustment_price )}}
        </td>
        <td align="right">
            ¥{{ number_format($billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->monthly_contract_fee  + $billing->adjustment_price )}}
        </td>
        <td>
            手数料　{{$billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->fee_rate}}%
        </td>
    </tr>
    @if (!empty($billing->hospital->hospital_option_plans) )
        @foreach( $billing->hospital->hospital_option_plans as $hospital_option_plan)
            <tr>
                <td>
                    {{ $hospital_option_plan->option_plan->option_plan_name }}
                </td>
                <td>
                    @if ($hospital_option_plan->option_plan_id != 6)
                        月額費用
                    @elseif($hospital_option_plan->price > 0)
                        月額費用
                    @else
                        従量課金
                    @endif
                </td>
                <td align="center">
                    @if ($hospital_option_plan->option_plan_id != 6)
                        1
                    @elseif($hospital_option_plan->price > 0)
                        1
                    @else
                        {{$special_count}}
                    @endif
                </td>
                <td align="right">
                    @if ($hospital_option_plan->option_plan_id != 6)
                        {{ number_format($hospital_option_plan->price) }}
                    @elseif($hospital_option_plan->price > 0)
                        {{ number_format($hospital_option_plan->price) }}
                    @else
                        {{ number_format($hospital_option_plan->pay_per_use_price) }}
                    @endif
                </td>
                <td align="right">
                    @if ($hospital_option_plan->option_plan_id != 6)
                        {{ number_format($hospital_option_plan->price) }}
                    @elseif($hospital_option_plan->price > 0)
                        {{ number_format($hospital_option_plan->price) }}
                    @else
                        {{ number_format($hospital_option_plan->pay_per_use_price * $special_count) }}
                    @endif
                </td>
                <td>　</td>
            </tr>
        @endforeach
    @endif

</table>
<br/><br/>
予約受付別明細
<table border="1" style="border-collapse: collapse">
    <tr class="plan_head">
        <th width="5%">予約番号</th>
        <th width="10%">媒体<br/>HPﾘﾝｸ/特集</th>
        <th width="5%">受診日</th>
        <th width="10%">受診者名</th>
        <th width="10%">受診者名（かな）</th>
        <th width="30%">コース</th>
        <th width="10%">コース金額<br>（税込）</th>
        <th width="10%">オプション金額<br>（税込）</th>
        <th width="10%">手数料<br>（税込）</th>
    </tr>
    @if (! $billing->hospital->reservationByCompletedDate($startedDate, $endedDate)->isEmpty() )
        @foreach( $billing->hospital->reservationByCompletedDate($startedDate, $endedDate) as $reservation)
            <tr>
                <td>{{ $reservation->id }}</td>
                <td align="center">{{ ( isset($reservation->channel) && ( $reservation->channel == 1)) ? 'WEB' : 'TEL' }}<br>@if ($reservation->site_code == 'HP') HPリンク @elseif ($reservation->site_code == 'special') 特集 @else　@endif</td>
                <td>{{ $reservation->reservation_date->format('Y/m/d') }}</td>
                <td>{{ $reservation->customer->family_name . ' ' . $reservation->customer->first_name }}</td>
                <td>{{ $reservation->customer->family_name_kana . ' ' . $reservation->customer->first_name_kana }}</td>
                <td>{{ $reservation->course->name }}</td>
                <td align="right">¥{{ number_format($reservation->tax_included_price + $reservation->adjustment_price) }}</td>
                <td align="right">{{ ( $reservation->reservation_options->pluck('option_price')->sum() ) ? '¥' . number_format($reservation->reservation_options->pluck('option_price')->sum())  : '' }}</td>
                <td align="right">{{ ( isset($reservation->fee) ) ? '¥' . number_format($reservation->fee) : '' }}</td>
            </tr>
        @endforeach
    @endif
</table>



    <br>
    @if ($billing->hospital->payment_type == 0)
        【振込先】<br/>
        銀行名：りそな銀行　　　　口座番号：普通）1659966<br>
        支店名：市ヶ谷支店　　　　口座名義：エンパワーヘルスケア(カ<br>

    @elseif($billing->hospital->payment_type == 1)
        【引落名義】<br/>
        エンパワーヘルスケア 株式会社<br>
        エンパワーヘルスケア（カ<br>
    @endif

</div>


</body>
</html>
