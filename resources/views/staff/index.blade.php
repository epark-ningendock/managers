<body>
  <!-- adminlte::pageを継承 -->
  @extends('adminlte::page')

  <!-- ページタイトルを入力 -->
  @section('title', 'Epark')

  <!-- ページの見出しを入力 -->
  @section('content_header')
    {{-- <h1>Dashboard</h1> --}}
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
            <span class="label label-success">新規作成</span>
            <table id="example2" class="table table-bordered table-hover">
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
                <th>{{ $staff['id'] }}</th>
                <th>{{ $staff['name'] }}</th>
                <th>{{ $staff['login_id'] }}</th>
                <th>{{ $staff['authority'] }}</th>
                {{-- 仕様確定次第実装 --}}
                <th>{{ $staff['authority'] }}</th>
                <th>{{ $staff['authority'] }}</th>
                <th>{{ $staff['authority'] }}</th>
                <th>{{ $staff['authority'] }}</th>
                <th>{{ $staff['authority'] }}</th>
                <th>{{ $staff['status'] ? '有効' : '無効' }}</th>
                <th><span class="label label-primary">編集</span></th>
                <th><span class="label label-danger">削除</span></th>
              </tr>
              @endforeach
              <tr>
            </table>
            <ul class="pagination pagination-sm inline">
              <li><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  @stop

  <!-- 読み込ませるCSSを入力 -->
  @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
  @stop

  <!-- 読み込ませるJSを入力 -->
  @section('js')
    <script> console.log('Hi!'); </script>
  @stop
</body>
