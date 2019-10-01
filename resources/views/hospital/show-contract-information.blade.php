@extends('layouts.form')

@section('content_header')
    <h1>契約情報</h1>
@stop

@section('form')

    @includeIf('hospital.partials.nav-bar')

    <div class="form-entry">
        <div class="box-body contract-show">
            <h2>契約情報</h2>

        <legend>物件番号</legend>
        <div>
            <p>{{ $contract_information->property_no ?? '' }}</p>
        </div>

        <legend>ドックネットID</legend>
        <div><p>{{ $contract_information->code ?? '' }}</p></div>

        <legend>契約者名（フリガナ）</legend>
        <div><p>{{ $contract_information->contractor_name_kana ?? '' }}</p></div>

        <legend>契約者名</legend>
        <div><p>{{ $contract_information->contractor_name ?? '' }}</p></div>

        <legend>申込日</legend>
        <div><p>{{ ( isset($contract_information->application_date)) ? $contract_information->application_date->format('Y-m-d') : '' }}</p></div>

        <legend>課金開始日</legend>
        <div><p>{{ ( isset($contract_information->billing->start_date)) ? $contract_information->billing->start_date->format('Y-m-d') : '' }}</p></div>


        <legend>解約日</legend>
        <div><p>{{ ( isset($contract_information->cancellation_date)) ? $contract_information->cancellation_date->format('Y-m-d') : '' }}</p></div>

        <h2>契約者情報</h2>

            <legend>代表者名（フリガナ）</legend>
            <div><p>{{ $contract_information->representative_name_kana ?? '' }}</p></div>

            <legend>代表者名</legend>
            <div><p>{{ $contract_information->representative_name ?? '' }}</p></div>
            <legend>屋号</legend>
            <div><p>{{ $hospital->name ?? '' }}</p></div>


            <legend>郵便番号</legend>
            <div><p>{{ $contract_information->postcode ?? '' }}</p></div>

            <legend>住所</legend>
            <div><p>{{ $contract_information->address ?? '' }}</p></div>

            <legend>電話番号</legend>
            <div><p>{{ $contract_information->tel ?? '' }}</p></div>


            <legend>FAX番号</legend>
            <div><p>{{ $contract_information->fax ?? '' }}</p></div>

            <legend>メールアドレス</legend>
            <div><p>{{ $contract_information->email ?? '' }}</p></div>

            <h2>プラン</h2>
            <legend>プラン名</legend>
            <div><p>{{ $contract_information->contract_plan->plan_name ?? '' }}</p></div>
            <legend>サービス開始日</legend>
            <div><p>{{ ( isset($contract_information->service_start_date)) ? $contract_information->service_start_date->format('Y-m-d') : '' }}</p></div>
            <legend>サービス終了日</legend>
            <div><p>{{ ( isset($contract_information->service_end_date)) ? $contract_information->service_end_date->format('Y-m-d') : '' }}</p></div>

        </div>
    </div>

@stop