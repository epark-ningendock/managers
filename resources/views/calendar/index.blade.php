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
              <span>{{ $calendar->name }}</span>
            「カレンダー表示 : {{ $calendar->is_calendar_display->description }}」</h3>

          <div class="box-tools">
            <div class="input-group input-group-sm hidden-xs">


              <a class="btn btn-primary btn-mini" href="{{ route('calendar.edit', $calendar->id) }}">
                <i class="fa fa-edit"> 編集</i>
              </a>
              <a class="btn btn-primary ml-2 btn-mini" href="{{ route('calendar.setting', $calendar->id) }}">カレンダー設定</a>

            </div>
          </div>
        </div>
        <!-- /.box-header -->
        @if(!$calendar->courses->isEmpty())
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tbody><tr>
                <th>検査コースID</th>
                <th>検査コース名</th>
                <th>WEB受付</th>
              </tr>
              @foreach($calendar->courses as $course)
              <tr>
                <td>{{ $course->id }}</td>
                <td>{{ $course->name }}</td>
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