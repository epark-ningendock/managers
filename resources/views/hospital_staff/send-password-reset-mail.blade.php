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
            <div class="login-logo">
                <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
            </div>
            <div class="box-body">
                <h2 class="">パスワード再発行</h2>
                <p>ご登録のメールアドレスを入力してください。<br>
                パスワード再設定用のメールをお送りいたします。</p>
                <form class="mt-4" method="GET"  action="{{ route('hospital-staff.send.password-reset') }}">
                    {{ csrf_field() }}
                    <label for="email">メールアドレス<span class="form_required">必須</span></label>
                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                        <input type="email" class="form-control" id="email" name="email" value="{{ Input::old('email') }}" placeholder="epark@example.com">
                        @if ($errors->has('email')) <p class="help-block strong">{{ $errors->first('email') }}</p> @endif
                    </div>
                    <div class="login-form-submit">
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

.margin-right-30 {
  margin-left: 30%;
}
</style>