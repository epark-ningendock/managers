@php
  use \App\Enums\Authority;
  use \App\Enums\Permission;

  $params = [
              'delete_route' => 'calendar.destroy',
            ];
@endphp

@extends('layouts.list', $params)

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>カレンダー管理</h1>
@stop

<!-- search section -->
@section('search')
  <h4>カレンダーの登録</h4>
  <div class="mb-4">下記のボタンをクリックすると新しいカレンダーを登録することができます。</div>
  <a class="btn btn-success" href="{{ route('calendar.create') }}">カレンダーの新規登録</a>
  <a class="btn btn-primary ml-2">休日確定</a>
@stop

@section('table')
  <div class="box box-solid mt-4" id="accordion">
    <table class="table table-bordered">
      <tbody>
      @foreach($calendars as $index => $calendar)
        <tr>
          <td colspan="3" style="background-color:#f4f4f4;">
            <div>
              <h4 style="display:inline-block;">
                <a href="#">
                  {{ $calendar->name }}
                </a>
                「カレンダー受付可否 : {{ $calendar->is_calendar_display->description }}」
              </h4>
              <a class="btn btn-primary pull-right" href="{{ route('calendar.setting', $calendar->id) }}">カレンダー確定</a>
            </div>
          </td>
        </tr>
        @if($calendar->courses->isNotEmpty())
          <tr>
            <th>検査コースID</th>
            <th>検査コース名</th>
            <th>WEB受付</th>
          </tr>
          @foreach($calendar->courses as $course)
            <tr>
              <td>{{ $course->id }}</td>
              <td>{{ $course->name }}</td>
              <td>{{ $course->web_reception->description }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="3"></td>
          </tr>
        @endif
      @endforeach
      </tbody>
    </table>
  </div>
@stop