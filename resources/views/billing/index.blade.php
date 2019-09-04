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
        </tr>
        </thead>
        <tbody>
        @if ( isset($billings) && count($billings) > 0 )
            @foreach ($billings as $billing)
                <tr class="billing-id-{{ $billing->id }}">
                    <td>{{ $billing->hospital->contract_information->property_no ?? '' }}</td>
                    <td>{{ $billing->hospital->name }}</td>
                    <td>{{ \App\Enums\BillingStatus::getDescription((int)$billing->contractPlan->status) }}</td>
                    <td>{{ $billing->contractPlan->plan_name }}</td>
                    <td>Commission total amount + Plan Amount</td>
                    <td>Plan amount of money</td>
                    <td>Commission total amount</td>
                    <td>{{ $billing->contractPlan->fee_rate }}</td>
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
