@php
  use \App\Enums\Authority;
  use \App\Enums\Permission;

  $params = [
              'delete_route' => 'calendar.destroy',
            ];
@endphp

@extends('layouts.calendar', $params)

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>
    <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
    -
    <span>カレンダー管理</span>
  </h1>
@stop

<!-- search section -->
@section('search')
  <div class="row" id="calendar_header">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header">
          <h2 class="box-title">
            カレンダーの登録
          </h2>
        </div>
        <div class="box-body">
          <p class="mb-4">下記のボタンをクリックすると新しいカレンダーを登録することができます。</p>
          <a class="btn btn-success" href="{{ route('calendar.create') }}">カレンダーの新規登録</a>
          <a class="btn btn-primary ml-2" href="{{ route('calendar.holiday') }}">休日設定</a>
        </div>
      </div>
    </div>
  </div>
@stop

@section('table')
  <div class="row edit-calendar">
    <div class="col-xs-12">
      @foreach($calendars as $index => $calendar)
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">
              <span><a href="{{ route('calendar.edit', $calendar->id) }}">{{ $calendar->name }}</a></span>
            （ {{ $calendar->is_calendar_display->description }}）</h3>

          <div class="box-tools">
            <div class="input-group input-group-sm hidden-xs">
              <strong>@if($calendar->auto_update_flg === 1)自動更新　@endif
              <a class="btn btn-primary btn-mini" href="{{ route('calendar.setting', $calendar->id) }}">カレンダー設定</a>
              <button class="btn btn-primary btn-mini ml-2 delete-btn calendar-delete-popup-btn" data-id="{{ $calendar->id }}">
                  <i class="fa fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
        <!-- /.box-header -->
        @if(!$calendar->courses->isEmpty())
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tbody><tr>
                <th style="width: 10em;text-align: left">検査コースID</th>
                <th style="text-align: left">検査コース名</th>
                <th style="width: 7em;text-align: left">価格</th>
                <th style="width: 7em">WEB受付</th>
              </tr>
              @foreach($calendar->courses as $course)
              <tr>
                <td style="text-align: left">{{ $course->id }}</td>
                <td style="text-align: left">{{ $course->name }}</td>
                <td style="text-align: left">{{ number_format((int)$course->price) }}円</td>
                <td><span class="label label-danger">{{ $course->web_reception->description }}</span></td>
              </tr>
              @endforeach
              </tbody></table>
          </div>
        @endif
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      @endforeach
    </div>
  </div>
@stop