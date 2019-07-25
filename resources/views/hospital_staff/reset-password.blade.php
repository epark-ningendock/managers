@extends('adminlte::master')

@section('adminlte_css')
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-box-body">
            <div class="login-logo">
                <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
            </div>
            {{-- <p class="text-bold text-center">パスワードリセットメール送信</p> --}}
            <div class="box-body">
                <form method="POST"  action="{{ route('hospital-staff.reset.password', ['email' => $email]) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <label for="password">新しいパスワード</label>
                    <div class="form-group @if ($errors->has('password')) has-error @endif">
                        <input id="password" type="password" class="form-control" name="password" required>
                        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                    </div>
                    
                    <label for="password-confirm">新しいパスワード（確認用）</label>
                    <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                    </div>
                    
                    
                    <div class="row mt-2">
                      <div class="col-xs-6">
                      </div>
                      <div class="col-xs-3">
                        <a href="/login" class="btn btn-default btn-block btn-flat margin-right-30">戻る</a>
                      </div>
                      <div class="col-xs-3">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">更新</button>
                      </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
  <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
  <script>
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>
  @yield('js')
@stop

<style>
    .width-400 {
        width: 400px;
    }

    .margin-right-30 {
        margin-left: 30%;
    }
</style>
