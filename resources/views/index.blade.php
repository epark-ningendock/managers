<body>
  <!-- adminlte::pageを継承 -->
  @extends('adminlte::page')

  <!-- ページタイトルを入力 -->
  @section('title', 'Epark')

  <!-- ページの見出しを入力 -->
  @section('content_header')
    <h1>The root index has not been decided yet.</h1>
  @stop

  <!-- ページの内容を入力 -->
  @section('content')
    <p>welcome</p>
  @stop

  <!-- 読み込ませるJSを入力 -->
  @section('js')
    <script> console.log('Hi!'); </script>
  @stop
</body>
