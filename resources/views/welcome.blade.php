<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
      <!-- adminlte::pageを継承 -->
      @extends('adminlte::page')

      <!-- ページタイトルを入力 -->
      @section('title', 'Dashboard')

      <!-- ページの見出しを入力 -->
      @section('content_header')
          <h1>Dashboard</h1>
      @stop

      <!-- ページの内容を入力 -->
      @section('content')
          <p>Welcome to this beautiful admin panel.</p>
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
</html>
