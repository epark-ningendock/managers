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
        <div class="login-box-body width-400">
            @include('layouts.partials.message')
            @include('layouts.partials.errorbag')
            <div class="login-logo">
                <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
            </div>
            {{-- <p class="text-bold text-center">パスワードリセットメール送信</p> --}}
            <div class="box-body">
                <form method="GET"  action="{{ route('hospital-staff.send.password-reset') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">・メールアドレスを入力してください</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Input::old('email') }}" placeholder="メールアドレスを入力してください">
                    </div>
                    
                    <div class="box-footer">
                        <a href="/login" class="btn btn-default">戻る</a>
                        <button type="submit" class="btn btn-primary">送信</button>
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
</style>