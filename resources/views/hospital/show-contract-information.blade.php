@extends('layouts.form')

@section('content_header')
    <h1>契約情報</h1>
@stop

@section('form')

    @includeIf('hospital.partials.nav-bar')

    <h5 class="sm-title" style="font-weight: bold;">契約情報</h5>


    <table class="table-borderless">
        <tr>
            <td style="width: 300px;">物件番号</td>
            <td>{{ $contract_information->property_no ?? '' }}</td>
        </tr>
        <tr>
            <td>ドックネットID</td>
            <td>{{ $contract_information->code ?? '' }}</td>

        </tr>
        <tr>
            <td>契約者名（フリガナ）</td>
            <td>{{ $contract_information->contractor_name_kana ?? '' }}</td>

        </tr>
        <tr>
            <td>契約者名</td>
            <td>{{ $contract_information->contractor_name ?? '' }}</td>

        </tr>
        <tr>
            <td>申込日</td>
            <td>{{ ( isset($contract_information->application_date)) ? $contract_information->application_date->format('Y-m-d') : '' }}</td>

        </tr>
        <tr>
            <td>課金開始日</td>
            <td>{{ ( isset($contract_information->billing->start_date)) ? $contract_information->billing->start_date->format('Y-m-d') : '' }}</td>

        </tr>
        <tr>
            <td>解約日</td>
            <td>{{ ( isset($contract_information->cancellation_date)) ? $contract_information->cancellation_date->format('Y-m-d') : '' }}</td>

        </tr>
        <tr>
            <td colspan="2"><h5 style="text-align: left;font-weight: bold;">契約者情報</h5></td>
        </tr>
        <tr>
            <td>代表者名（フリガナ）</td>
            <td>{{ $contract_information->representative_name_kana ?? '' }}</td>

        </tr>
        <tr>
            <td>代表者名</td>
            <td>{{ $contract_information->representative_name ?? '' }}</td>

        </tr>
        <tr>
            <td>屋号</td>
            <td>{{ $hospital->name ?? '' }}</td>

        </tr>
        <tr>
            <td>郵便番号</td>
            <td>{{ $contract_information->postcode ?? '' }}</td>
        </tr>
        <tr>
            <td>住所</td>
            <td>{{ $contract_information->address ?? '' }}</td>

        </tr>
        <tr>
            <td>電話番号</td>
            <td>{{ $contract_information->tel ?? '' }}</td>

        </tr>
        <tr>
            <td>FAX番号</td>
            <td>{{ $contract_information->fax ?? '' }}</td>
        </tr>
        <tr>
            <td>メールアドレス</td>
            <td>{{ $contract_information->email ?? '' }}</td>
        </tr>

        <tr>
            <td colspan="2"><h5 style="text-align: left;font-weight: bold;">プラン</h5></td>
        </tr>
        <tr>
            <td>プラン名</td>
            <td>{{ $contract_information->contract_plan->plan_name ?? '' }}</td>

        </tr>
        <tr>
            <td>サービス開始日</td>
            <td>{{ ( isset($contract_information->service_start_date)) ? $contract_information->service_start_date->format('Y-m-d') : '' }}</td>
        </tr>

        <tr>
            <td>サービス終了日</td>
            <td>{{ ( isset($contract_information->service_end_date)) ? $contract_information->service_end_date->format('Y-m-d') : '' }}</td>
        </tr>


    </table>



@stop