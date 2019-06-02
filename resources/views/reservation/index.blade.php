@extends('layouts.list')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>予約一覧</h1>
@stop

@section('search')

    <form action="{{ route('reservation.index') }}">

        <div class="std-container">
            <div class="row">

                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="s_text">医療機関名・ID</label>
                        <input type="text" class="form-control" autocomplete="off" name="s_text" id="s_text"
                               value="{{ request('s_text') }}"/>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="status">状態</label>
                        <select name="status" id="status" class="form-control">
                            @foreach(\App\Enums\HospitalEnums::toArray() as $key)

                                <option
                                        value="{{ $key }}" {{ ( request('status') == $key) ? "selected" : "" }}>{{ \App\Enums\HospitalEnums::getDescription($key) }}</option>
                            @endforeach
                        </select>
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
        </div>

    </form>

@stop


@section('table')

    <div class="table-responsive">
        @if (count($reservations))
        <table id="example2" class="table table-bordered table-hover mb-5 mt-5">
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
                    <th>{{ $reservation->id }}</th>
                    <th>{{ $reservation->completed_date }}</th>
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
