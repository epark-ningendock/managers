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
    <div class="login-box-body width-400 font-size">
      @include('layouts.partials.message')
      <div class="login-logo">
        <a href="{{ route('login') }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
      </div>
      <form action="{{ route('postLogin') }}" method="post">
        {!! csrf_field() !!}
        <div class="form-group has-feedback text-center @if ($errors->has('fail_login')) has-error @endif">
          @if ($errors->has('fail_login')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('fail_login') }}</p> @endif
        </div>
        <div class="form-group has-feedback @if ($errors->has('login_id')) has-error @endif">
          <label for="login_id">ログインID</label>
          <input type="text" name="login_id" class="form-control" value="{{ old('login_id') }}"
               placeholder="ログインIDを入力してください">
          @if ($errors->has('login_id')) <p class="help-block strong"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('login_id') }}</p> @endif
        </div>
        <div class="form-group has-feedback @if ($errors->has('password')) has-error @endif">
          <label for="login_id">パスワード</label>
          <input type="password" name="password" class="form-control"
               placeholder="パスワードを入力してください">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          @if ($errors->has('password'))<p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('password') }}</p>@endif
        </div>
        <div class="login-form-submit">
          <button type="submit" class="btn btn-primary">ログイン</button>
        </div>
      </form>
      <div class="auth-links">
        <a href="{{ route('hospital-staff.show.password-reset') }}"
           class="text-center"
        >パスワードをお忘れの方はこちら！</a>
        <br>
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
</style>