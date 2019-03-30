<body>
  <!-- adminlte::pageを継承 -->
  @extends('adminlte::page')

  <!-- ページタイトルを入力 -->
  @section('title', 'Epark')

  <!-- ページの見出しを入力 -->
  @section('content_header')
    <h1>Dashboard</h1>
  @stop

  <!-- ページの内容を入力 -->
  @section('content')
    <p>{{ $hello }}</p>
      @foreach ($hello_array as $hello_word)
          {{ $hello_word }}<br>
      @endforeach
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Hover Data Table</h3>
          </div>
          <!-- /.box-header -->
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
              <tr>
                <th>1</th>
                <th>西村竜</th>
                <th>epark-dev</th>
                <th>epark-dev@example.com</th>
                <th>A</th>
                <th>A</th>
                <th>A</th>
                <th>A</th>
                <th>A</th>
                <th>A</th>
                <th>A</th>
                <th>A</th>
                <th>A</th>
              </tr>
              <tr>
            </table>
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
