@php
  use App\Enums\Status;
  use App\Enums\Authority;
  use App\Enums\Permission;

  $params = [
              'delete_route' => 'staff.destroy',
              'create_route' => 'staff.create',
              'delete_confirm_message' => 'スタッフの情報を削除しますか？'
            ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>スタッフ管理</h1>
@stop

<!-- search section -->
@section('search')
  <form role="form">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="name">スタッフ名</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="スタッフ名" value="{{ $name or '' }}">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="login_id">ログインID</label>
          <input type="text" class="form-control" id="login_id" name="login_id" placeholder="ログインID"
                 value=" {{ $login_id or '' }}">
        </div>
      </div>
      <div class="col-md-3">
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
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary btn-search">サーチ</button>
      </div>
    </div>
  </form>
@stop

@section('table')
  <table id="example2" class="table table-bordered table-hover">
    <thead>
    <tr>
      <th>No</th>
      <th>スタッフ名</th>
      <th>ログインID</th>
      <th>メールアドレス</th>
      <th>権限</th>
      <th>医療機関管理</th>
      <th>スタッフ管理</th>
      <th>検査コース分類</th>
      <th>請求管理</th>
      <th>事前決済管理</th>
      <th>状態</th>
      <th>編集</th>
      <th>削除</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($staffs as $staff)
      <tr class="{{ $staff->status->is(Status::Deleted) ? 'dark-gray' : ($staff->status->is(Status::Invalid) ? 'light-gray' : '') }}">
        <td>{{ $staff->id }}</td>
        <td>{{ $staff->name }}</td>
        <td>{{ $staff->login_id }}</td>
        <td>{{ $staff->email }}</td>
        <td>{{ $staff->authority->description }}</td>
        <td>{{ Permission::getInstance($staff->staff_auth->is_hospital)->description }}</td>
        <td>{{ Permission::getInstance($staff->staff_auth->is_staff)->description }}</td>
        <td>{{ Permission::getInstance($staff->staff_auth->is_item_category)->description }}</td>
        <td>{{ Permission::getInstance($staff->staff_auth->is_invoice)->description }}</td>
        <td>{{ Permission::getInstance($staff->staff_auth->is_pre_account)->description }}</td>
        <td>{{ $staff->status->description  }}</td>
        <td>
{{--          @if(!$staff->status->is(Status::Deleted) && auth()->check() && auth()->user()->hasPermission('is_staff', Permission::Edit))--}}
            <a class="btn btn-primary"
                 href="{{ route('staff.edit', $staff->id) }}">
              編集
            </a>
          {{--@endif--}}
        </td>
        <td>
{{--          @if(!$staff->status->is(Status::Deleted) && auth()->check() && auth()->user()->hasPermission('is_staff', Permission::Edit))--}}
            <button class="btn btn-danger delete-btn" data-id="{{ $staff->id }}">
              削除
            </button>
          {{--@endif--}}
        </td>
      </tr>
    @endforeach
    <tr>
  </table>
  {{ $staffs->links() }}
@stop

<style>
  tr.light-gray td {
    background-color: lightgray;
  }
  tr.dark-gray td{
    background-color: darkgray;
  }
</style>