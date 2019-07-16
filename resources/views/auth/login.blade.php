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
    <!-- /.login-logo -->
    <div class="login-box-body width-400 font-size">
      @include('layouts.partials.message')
      @include('layouts.partials.errorbag')
      <div class="login-logo">
        <a href="{{ route('login') }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
      </div>
      {{-- <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p> --}}
      <form action="{{ route('postLogin') }}" method="post">
        {!! csrf_field() !!}

        <div class="form-group has-feedback {{ $errors->has('login_id') ? 'has-error' : '' }}">
          @if ($errors->has('fail_login'))
            <span class="help-block">
              <strong>{{ $errors->first('fail_login') }}</strong>
            </span>
          @endif
          <input type="text" name="login_id" class="form-control" value="{{ old('login_id') }}"
               placeholder="ログインIDを入力してください">
          <!-- <span class="glyphicon glyphicon-envelope form-control-feedback"></span> -->
        </div>
        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
          <input type="password" name="password" class="form-control"
               placeholder="パスワードを入力してください">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            {{-- <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}
              </label>
            </div> --}}
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit"
                class="btn btn-primary btn-block btn-flat">ログイン</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="auth-links">
        <a href="{{ route('hospital-staff.show.password-reset') }}"
           class="text-center"
        >パスワードをお忘れの方はこちら！</a>
        <br>
        <!-- @if (config('adminlte.register_url', 'register'))
          <a href="{{ url(config('adminlte.register_url', 'register')) }}"
             class="text-center"
          >{{ trans('adminlte::adminlte.register_a_new_membership') }}</a>
        @endif -->
      </div>
    </div>
    <!-- /.login-box-body -->
  </div><!-- /.login-box -->
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