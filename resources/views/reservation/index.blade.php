@extends('layouts.list')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>予約一覧</h1>
@stop

@section('search')

    {{ Form::open(['route' => 'reservation.index', 'method' => 'get',]) }}

        <div class="std-container">
            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        {{ Form::label('claim_month-field', '請求月') }}
                        {{ Form::text('claim_month', old('claim_month'), ['class' => 'form-control', 'id' => 'claim_month-field', 'placeholder' => '2019/04']) }}
                    </div>
                </div>

                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="s_text">出力</label>
                        <div>
                        {{Form::button('確定明細表出力', ['class' => 'btn btn-default'])}}
                        {{Form::button('予約内訳明細表出力', ['class' => 'btn btn-default'])}}
                        {{Form::button('運用分析用一覧出力', ['class' => 'btn btn-default'])}}
                        </div>
                    </div>
                </div>

                <div class="col-sm-9 form-inline">
                    <label for="s_text">予約日</label>
                        <div class="form-inline">
                        {{ Form::text('reservation_date_start', old('reservation_date_start'), ['class' => 'form-control', 'id' => 'reservation_date_start-field', 'placeholder' => '2019-04-01']) }}
                        {{ Form::text('reservation_date_end', old('reservation_date_end'), ['class' => 'form-control', 'id' => 'reservation_date_end-field', 'placeholder' => '2019-04-30']) }}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="s_text">医療機関名</label>
                        <input type="text" class="form-control" autocomplete="off" name="s_text" id="s_text"
                               value="{{ request('s_text') }}"/>
                    </div>
                </div>

                <div class="col-sm-9 form-inline">
                    <label for="s_text">来院日</label>
                    <div class="form-inline">
                        {{ Form::text('completed_date_start', old('completed_date_start'), ['class' => 'form-control', 'id' => 'completed_date_start-field', 'placeholder' => '2019-04-01']) }}
                        {{ Form::text('completed_date_end', old('completed_date_end'), ['class' => 'form-control', 'id' => 'completed_date_end-field', 'placeholder' => '2019-04-30']) }}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="s_text">顧客ID</label>
                        {{ Form::text('customer_id', old('customer_id'), ['class' => 'form-control', 'id' => 'customer_id-field', 'placeholder' => '1123']) }}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="s_text">受診者名</label>
                        {{ Form::text('customer_name', old('customer_name'), ['class' => 'form-control', 'id' => 'customer_name-field', 'placeholder' => '山田太郎']) }}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="s_text">生年月日</label>
                        {{ Form::text('birthday', old('birthday'), ['class' => 'form-control', 'id' => 'birthday-field', 'placeholder' => '19850405']) }}
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="reset" class="btn btn-default">検索用にクリア</button>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    検索する
                </button>
            </div>
        </div>

    {{ Form::close() }}

@stop


@section('table')

    <div class="table-responsive">
        @if (count($reservations))
        <table class="table table-bordered table-hover mb-5 mt-5">
            <thead>
            <tr>
                <th>予約日</th>
                <th>来院日</th>
                <th>医療機関名</th>
                <th>媒体</th>
                <th>区分</th>
                <th>予約コース</th>
                <th>手数料(税抜)</th>
                <th>請求金額</th>
                <th>受信者名</th>
                <th>請求月</th>
            </tr>
            </thead>
            <tbody>
            <tbody>
                @foreach ($reservations as $key => $reservation)
                <tr>
                    <th>{{ $reservation->reservation_date }}</th>
                    <th>{{ $reservation->completed_date->format('Y/m/d') }}</th>
                    <th>{{ $reservation->hospital->name }}</th>
                    <th>
                        {{ \App\Reservation::getChannel($reservation->channel) }}
                    </th>
                    <th>{{ $reservation->is_reservation }}</th>
                    <th>{{ $reservation->course->name }}</th>
                    <th>{{ $reservation->course->price }}</th>
                    <th>請求金額</th>
                    <th>
                        @isset($reservation->customer->name)
                        {{ $reservation->customer->name }}
                        @endisset
                    </th>
                    <th>{{ $reservation->claim_month }}</th>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $reservations->links() }}
        </div>
        @else
            <p>
                予約情報がありません。
            </p>
        @endif

    </div>


@stop
