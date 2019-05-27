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
  <a class="btn btn-primary" href="{{ route('calendar.create') }}">カレンダーの新規登録</a>
  <a class="btn btn-primary ml-2">休日確定</a>
@stop

@section('table')
  <h4>カレンダーの一覧と編集</h4>
  <div class="mb-4">カレンダーの並び替えボタンを押下するとカレンダーの順番を並び替えることができます。</div>
  <a class="btn btn-success">カレンダーの並び替え</a>
  <div class="box box-solid mt-4" id="accordion">
    <table class="table table-bordered">
      <tbody>
      @foreach($calendars as $index => $calendar)
        <tr>
          <td>
            <div>
              <h4 style="display:inline-block;">
                <a data-toggle="collapse" href="#collapse_{{ $index }}"
                   aria-expanded="false">
                  {{ $calendar->name }}
                </a>
                「カレンダー受付可否 : {{ $calendar->is_calendar_display->description }}」
              </h4>
              <button class="btn btn-default pull-right">カレンダー確定</button>
            </div>

            <div id="collapse_{{ $index }}" class="panel-collapse collapse">
              <hr style="margin: 5px -10px;"/>
              @if($calendar->courses->isNotEmpty())
                <div class="table-responsive mt-4">
                  <table id="example2" class="table table-bordered">
                    <thead>
                    <tr>
                      <th>検査コースID</th>
                      <th>検査コース名</th>
                      <th>WEB受付</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($calendar->courses as $course)
                      <tr>
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->web_reception->description }}</td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@stop