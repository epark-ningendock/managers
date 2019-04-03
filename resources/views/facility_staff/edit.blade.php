<body>
<!-- adminlte::pageを継承 -->
@extends('adminlte::page')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>医療スタッフを編集 - {{ $facility_staff->name }}</h1>
@stop

<!-- ページの内容を入力 -->
@section('content')

    <div class="box box-primary">

        @if ($errors->any())
            <br/>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"  action="{{ route('facility-staff.update', $facility_staff->id) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="box-body">

                <div class="form-group @if ($errors->has('name')) has-error @endif">
                    <label for="name">名前を入力</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ ( $facility_staff->name ) ? $facility_staff->name : Input::old('name') }}" placeholder="名前を入力">
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <label for="email">メールアドレスを入力して</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ ( $facility_staff->email ) ? $facility_staff->email : Input::old('email') }}" placeholder="メールアドレスを入力して">
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('login_id')) has-error @endif">
                    <label for="login_id">ログインID</label>
                    <input type="text" class="form-control" id="login_id" name="login_id" value="{{ ( $facility_staff->login_id ) ? $facility_staff->login_id : Input::old('login_id') }}" placeholder="ログインID">
                    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('password')) has-error @endif">
                    <label for="password">パスワード</label>
                    <input type="password" class="form-control" id="password" name="password" value="{{ ( $facility_staff->password ) ? $facility_staff->password : Input::old('password') }}" placeholder="パスワード">
                    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                </div>

                <div class="box-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-default">バック</a>
                    <button type="submit" class="btn btn-primary">つくる</button>
                </div>

            </div>



        </form>

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
