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
        {{--<div class="box-header with-border">--}}
          {{--<h3 class="box-title">スタッフ管理</h3>--}}
        {{--</div>--}}
        <!-- /.box-header -->

          <!-- Message -->
        @if (session('success'))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
            {{ session('success') }}
          </div>
          <br/>
        @endif
        @if (session('error'))
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
            {{ session('error') }}
          </div>
          <br/>
        @endif
        <div class="box-header">
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
          <a class="btn btn-success" href="{{ route('staff.create') }}">新規作成</a>
        </div>
        <div class="box-body">
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
              <tr>
                <th>{{ $staff->id }}</th>
                <th>{{ $staff->name }}</th>
                <th>{{ $staff->login_id }}</th>
                <th>{{ $staff->email }}</th>
                <th>{{ $staff->authority }}</th>
                <th>{{ $staff->staff_auth['is_hospital'] }}</th>
                <th>{{ $staff->staff_auth['is_staff'] }}</th>
                <th>{{ $staff->staff_auth['is_item_category'] }}</th>
                <th>{{ $staff->staff_auth['is_invoice'] }}</th>
                <th>{{ $staff->staff_auth['is_pre_account'] }}</th>
                <th>{{ \App\Enums\Status::getDescription($staff->status) }}</th>
                <th><a class="btn btn-primary" href="{{ route('staff.edit', $staff->id) }}">編集</a></th>
                <th>
                  <button class="btn btn-danger delete-btn" data-id="{{ $staff->id }}">削除</button>
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
  <form action="{{ route('staff.destroy', ':id') }}" method="post" id="delete-form">
    {{csrf_field()}}
    {{ method_field('DELETE') }}
  </form>
@stop

<!-- 読み込ませるJSを入力 -->
@section('js')
  <script>
      $(document).ready(function() {
         $('.delete-btn').click(function() {
             const id = $(this).data('id');
             Modal.showConfirm('スタッフの情報を削除しますか？', function() {
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