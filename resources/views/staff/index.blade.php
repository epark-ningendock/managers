@extends('layouts.master')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
   <h1>スタッフ管理</h1>
@stop

<!-- ページの内容を入力 -->
@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">スタッフ管理</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
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
                  <input type="text" class="form-control" id="login_id" name="login_id" placeholder="ログインID" value=" {{ $login_id or '' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="status">状態</label>
                  <select class="form-control" id="status" name="status">
                    @foreach(\App\Enums\Status::toArray() as $key)
                      <option value="{{ $key }}" {{ (isset($status) && $status == $key) ? "selected" : "" }}>{{ \App\Enums\Status::getDescription($key) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-search" >サーチ</button>
              </div>
            </div>
          </form>
          <span class="label label-success">新規作成</span>
          <table id="example2" class="table table-bordered table-hover mt-3">
            <thead>
            <tr>
              <th>No</th>
              <th>スタッフ名</th>
              <th>ログインID</th>
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
              <tr>
                <th>{{ $staff->id }}</th>
                <th>{{ $staff->name }}</th>
                <th>{{ $staff->login_id }}</th>
                <th>{{ $staff->authority }}</th>
                <th>{{ $staff->staff_auth['is_hospital'] }}</th>
                <th>{{ $staff->staff_auth['is_staff'] }}</th>
                <th>{{ $staff->staff_auth['is_item_category'] }}</th>
                <th>{{ $staff->staff_auth['is_invoice'] }}</th>
                <th>{{ $staff->staff_auth['is_pre_account'] }}</th>
                <th>{{ \App\Enums\Status::getDescription($staff->status) }}</th>
                <th><span class="label label-primary">編集</span></th>
                <th>
                  <span class="label label-danger delete-btn" data-id="{{ $staff->id }}">削除</span>
                </th>
              </tr>
            @endforeach
            <tr>
          </table>
          {{ $staffs->links() }}
        </div>
      </div>
    </div>
  </div>
  <form action="{{ action('StaffController@destroy', ':id') }}" method="post" id="delete-form">
    {{csrf_field()}}
    <input name="_method" type="hidden" value="DELETE">
  </form>
@stop

<!-- 読み込ませるJSを入力 -->
@section('js')
  <script>
      $(document).ready(function() {
         $('.delete-btn').click(function() {
             const id = $(this).data('id');
             Modal.showConfirm('Do you want to delete staff?', function() {
                submitDeleteForm(id);
             });
         });
      });

      function submitDeleteForm(id) {
          const action = $('#delete-form').attr('action').replace(':id', id);
          $('#delete-form').attr('action', action);
          $('#delete-form').submit();
      }
  </script>
@stop