@php
    use App\TaxClass;
    use App\Enums\BillingStatus;
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
            <th>請求月</th>
            <th>請求ステータス</th>
            <th>プラン</th>
            <th>請求金額</th>
            <th>プラン金額</th>
            <th>オプションプラン金額</th>
            <th>HPリンク月額金額</th>
            <th>手数料合計金額</th>
            <th>成果コース</th>
            <th colspan="2"></th>
        </tr>
        </thead>
        <tbody>
        @if ( isset($billings) && count($billings) > 0 )
            @foreach ($billings as $billing)
                <tr class="billing-id-{{ $billing->id }} status-{{ $billing->status }}">
                    <td style="width: 80px;">{{  $billing->billing_month }}</td>
                    <td>{{ BillingStatus::getDescription($billing->status) }}</td>
                    @if (isset($billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan))
                        <td>
                            {{ $billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->plan_name }}
                        </td>
                        <td>
                            {{ number_format($billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->monthly_contract_fee +
                                $billing->hospital->reservationByCompletedDate($billing->startedDate, $billing->endedDate)->pluck('fee')->sum()
                                + $billing->adjustment_price
                                + $billing->hospital->hpLinkMonthPrice()
                                + $billing->hospital->hospitalOptionPlanPrice($billing->id, $billing->endedDate)
                                ) . '円  ' }}
                        </td>
                        <td>
                            {{ number_format($billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->monthly_contract_fee  + $billing->adjustment_price )}}円
                        </td>
                        <td>
                            {{ number_format($billing->hospital->hospitalOptionPlanPrice($billing->id, $billing->endedDate)) . '円'}}
                        </td>
                        <td>
                            {{ number_format($billing->hospital->hpLinkMonthPrice()). '円'}}
                        </td>
                        <td>
                            {{ number_format($billing->hospital->reservationByCompletedDate($billing->startedDate, $billing->endedDate)->pluck('fee')->sum()) }}円
                        </td>
                        <td>
                            {{ $billing->hospital->hospitalPlanByDate($billing->endedDate)->contractPlan->fee_rate }}%
                        </td>
                        <td>
                            <a href="{{ route('billing.show', ['billing' => $billing]) }}" class="btn btn-primary">明細</a>
                        </td>
                        <td>
                            <a href="{{ route('billing.status.update', array_merge( request()->all(), [ 'hospital_id' => $billing->hospital->id, 'billing' => $billing, 'status' => BillingStatus::CONFIRMED, 'claim_check' => 'yes'] )) }}"
                                class="btn @if( $billing->status != BillingStatus::CHECKING ) btn-default @else btn-primary @endif"
                                @if( $billing->status != BillingStatus::CHECKING ) style="pointer-events: none;" @endif
                            >請求確認</a>
                        </td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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



@stop
