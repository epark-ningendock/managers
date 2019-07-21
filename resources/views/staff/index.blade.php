@php
  use \App\Enums\StaffStatus;
  use \App\Enums\Authority;
  use \App\Enums\Permission;

  $params = [
              'delete_route' => 'staff.destroy',
              'create_route' => 'staff.create',
              'route' => 'staff'
            ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>
      <i class="fa fa-users"> スタッフ管理</i>
  </h1>
@stop

<!-- search section -->
@section('search')
  <form role="form">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="name">スタッフ名</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ $name or '' }}">
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
            @foreach(StaffStatus::toArray() as $key)
              <option
                  value="{{ $key }}" {{ (isset($status) && $status == $key) ? "selected" : "" }}>{{ StaffStatus::getDescription($key) }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary btn-search">
            <i class="glyphicon glyphicon-search"></i> 検索
        </button>
      </div>
    </div>
  </form>
@stop

@section('table')
  <div class="count-paginate-bar">
      <div class="row">
          <div class="col-sm-6">
              <div class="display-total text-left mr-5 ">
                  全{{ $staffs->total() }} 件中
                  {{ ( $staffs->currentPage() * $staffs->perPage() ) - $staffs->perPage() + 1 }}件
                  @if ($staffs->currentPage() === $staffs->lastPage())
                    ~ {{ $staffs->total() }} 件を表示
                  @else
                    ~ {{ $staffs->currentPage() * $staffs->perPage() }} 件を表示
                  @endif
              </div>
          </div>
          <div class="col-sm-6">
          </div>
      </div>
  </div>
  <div class="table-responsive mt-3">
    <table id="example2" class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
        <th>スタッフID</th>
        <th>スタッフ名</th>
        <th>ログインID</th>
        <th>メールアドレス</th>
        <th>部署</th>
        <th>権限</th>
        <th>医療機関管理</th>
        <th>スタッフ管理</th>
        <th>検査コース分類</th>
        <th>請求管理</th>
        <th>事前決済管理</th>
        <th>契約管理</th>
        <th>状態</th>
        @if (Auth::user()->staff_auth->is_staff === 3)
          <th>編集</th>
          <th>削除</th>
        @endif
        @if (Auth::user()->authority->value === Authority::Admin && Auth::user()->staff_auth->is_staff === 3)
          <th>パスワード変更</th>
        @endif
      </tr>
      </thead>
      <tbody>
      @foreach ($staffs as $staff)
      <tr class="{{ $staff->status->is(StaffStatus::Deleted) ? 'dark-gray' : ($staff->status->is(StaffStatus::Invalid) ? 'light-gray' : '') }}">
          <td>{{ $staff->id }}</td>
          <td>{{ $staff->name }}</td>
          <td>{{ $staff->login_id }}</td>
          <td>{{ $staff->email }}</td>
          <td>{{ isset($staff->department->name) ? $staff->department->name : '' }}</td>
          <td>{{ $staff->authority->description }}</td>
          <td>{{ Permission::getInstance($staff->staff_auth->is_hospital)->description }}</td>
          <td>{{ Permission::getInstance($staff->staff_auth->is_staff)->description }}</td>
          <td>{{ Permission::getInstance($staff->staff_auth->is_cource_classification)->description }}</td>
          <td>{{ Permission::getInstance($staff->staff_auth->is_invoice)->description }}</td>
          <td>{{ Permission::getInstance($staff->staff_auth->is_pre_account)->description }}</td>
          <td>{{ Permission::getInstance($staff->staff_auth->is_contract)->description }}</td>
          <td>{{ $staff->status->description  }}</td>
          @if (Auth::user()->staff_auth->is_staff === 3)
            <td>
              @if ( $staff->status->value !== StaffStatus::Deleted)
                <a class="btn btn-primary"
                  href="{{ route('staff.edit', $staff->id) }}">
                  <i class="fa fa-edit text-bold"> 編集</i>
                </a>
              @endif
            </td>
            <td>
              @if ( $staff->status->value !== StaffStatus::Deleted)
                <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $staff->id }}">
                  <i class="fa fa-trash"></i>
                </button>
              @endif
            </td>
          @endif
          
          @if (Auth::user()->authority->value === Authority::Admin && Auth::user()->staff_auth->is_staff === 3)
            <td>
              @if ( $staff->status->value !== StaffStatus::Deleted)
                <a href="{{ route('staff.edit.password', ['staff_id' =>  $staff->id]) }}" class="btn btn-success">
                  <i class="fa fa-key text-bold">パスワード</i>
                </a>
              @endif
            </td>
          @endif
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  {{ $staffs->links() }}
  <style>
    tr.light-gray td {
      background-color: lightgray;
    }
    tr.dark-gray td{
      background-color: darkgray;
    }
  </style>
@stop