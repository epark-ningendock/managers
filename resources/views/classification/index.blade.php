@php
  use App\Enums\Status;
  use App\Enums\Authority;
  use App\Enums\Permission;

  $params = [
              'delete_route' => 'classification.destroy',
              'create_route' => 'classification.create',
              'delete_params' => 'classification='.(isset($classification) ? $classification : 'minor')
            ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>検査コース分類管理</h1>
@stop

<!-- search section -->
@section('search')
  <form role="form">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="type">分類種別</label>
          <select class="form-control" id="type" name="type">
            <option value="">なし</option>
            @foreach($c_types as $c_type)
              <option
                  value="{{ $c_type->id }}" {{ (isset($type) && $type == $c_type->id) ? "selected" : "" }}>{{ $c_type->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="classification">分類</label>
          <select class="form-control" id="classification" name="classification">
            <option value="major" {{ (isset($classification) && $classification == 'major') ? "selected" : "" }}>大分類
            </option>
            <option value="middle" {{ (isset($classification) && $classification == 'middle') ? "selected" : "" }}>中分類
            </option>
            <option value="minor" {{ (!isset($classification) || $classification == 'minor') ? "selected" : "" }}>小分類
            </option>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="major">大分類</label>
          <select class="form-control" id="major" name="major">
            <option value="">なし</option>
            @foreach($c_majors as $c_major)
              <option
                  value="{{ $c_major->id }}" {{ (isset($major) && $major == $c_major->id) ? "selected" : "" }}>{{ $c_major->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="middle">中分類</label>
          <select class="form-control" id="middle" name="middle">
            <option value="">なし</option>
            @foreach($c_middles as $c_middle)
              <option
                  value="{{ $c_middle->id }}" {{ (isset($middle) && $middle == $c_middle->id) ? "selected" : "" }}>{{ $c_middle->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="status">状態</label>
          <select class="form-control" id="status" name="status">
            @foreach(Status::toArray() as $key)
              <option
                  value="{{ $key }}" {{ (isset($status) && $status == $key) ? "selected" : "" }}>{{ Status::getDescription($key) }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-primary btn-search">検索</button>
      </div>
    </div>
  </form>
@stop

@section('button')
  <div class="pull-right">
    <a class="btn btn-primary mr-2">並び替え</a>
    <a class="btn btn-success" href="{{ route('classification.create') }}">新規作成</a>
  </div>
@stop

@section('table')
  <table class="table table-bordered table-hover">
    <thead>
    <tr>
      <th>大分類</th>
      @if (!isset($classification) || $classification == 'minor' || $classification == 'middle')
        <th>中分類</th>
      @endif
      @if (!isset($classification) || $classification == 'minor')
        <th>小分類</th>
      @endif
      <th>更新日時</th>
      <th>医療機関管理</th>
      <th>編集</th>
      <th>{{ isset($status) && $status == Status::Deleted ? '復元' : '削除' }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($classifications as $item)
      <tr class="{{ $item['status']->is(Status::Deleted) ? 'dark-gray' : '' }}">
        <td>{{ $item['major_name'] }}</td>
        @if (!isset($classification) || $classification == 'minor' || $classification == 'middle')
          <td>{{ $item['middle_name'] }}</td>
        @endif
        @if (!isset($classification) || $classification == 'minor')
          <td>{{ $item['minor_name'] }}</td>
        @endif
        <td>{!! $item['updated_at'] !!} </td>
        <td>{{ $item['status']->description }}</td>
        <td>
          {{--@if($item['status']->is(Status::Valid) && auth()->check() && auth()->user()->hasPermission('is_item_category', Permission::Edit))--}}
          @if($item['status']->is(Status::Valid))
            <a class="btn btn-primary"
               href="{{ route('classification.edit', $item['id']) }}">
              編集
            </a>
          @endif
        </td>
        <td>
          {{--@if(auth()->check() && auth()->user()->hasPermission('is_item_category', Permission::Edit))--}}
          @if($item['status']->is(Status::Valid))
            <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $item['id'] }}"
                    data-message="{{ trans('messages.classification_delete_popup_content') }}">
              削除
            </button>
          @elseif($item['status']->is(Status::Deleted))
            <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $item['id'] }}"
                    data-target-form="#restore-record-form" data-message="{{ trans('messages.classification_restore_popup_content') }}">
              復元
            </button>
          @endif
          {{--@endif--}}
        </td>
      </tr>
    @endforeach
    @if($classifications->isEmpty())
      <tr>
        <td colspan="7" class="text-center">該当する分類はありませんでした</td>
      </tr>
    @endif
    </tbody>
  </table>
  @if ($result->hasPages())
    {{ $result->links() }}
  @endif
  <form id="restore-record-form" class="hide" method="POST"
        action="{{ route('classification.restore', ':id') }}">
    {{ csrf_field() }}
    {{--{{ method_field('PUT') }}--}}
    <input type="hidden" name="classification" value="{{ (isset($classification) ? $classification : 'minor') }}">
  </form>
  <style>
    tr.dark-gray td {
      background-color: darkgray;
    }
  </style>
@stop
@section('js')
  @parent
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // classification change
          -----------------------------------------------------*/
          (function () {
              const cEle = $('#classification');
              const onChange = function () {
                  if (cEle.val() == 'major') {
                      $('#major, #middle').attr('disabled', 'disabled');
                  } else if (cEle.val() == 'middle') {
                      $('#middle').attr('disabled', 'disabled');
                      $('#major').removeAttr('disabled');
                  } else {
                      $('#major, #middle').removeAttr('disabled');
                  }
              };
              cEle.change(function () {
                  onChange();
              });
              onChange();
          })();

      })(jQuery);
  </script>
@stop