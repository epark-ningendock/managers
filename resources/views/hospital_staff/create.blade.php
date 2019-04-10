<body>
  <!-- adminlte::pageを継承 -->
  @extends('adminlte::page')

  <!-- ページタイトルを入力 -->
  @section('title', 'Epark')

  <!-- ページの見出しを入力 -->
  @section('content_header')
     <h1>医療機関職員を作成する</h1>
  @stop




  <!-- ページの内容を入力 -->
  @section('content')

      <div class="box box-primary">

          @include('hospital_staff.partial.create-form')

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
