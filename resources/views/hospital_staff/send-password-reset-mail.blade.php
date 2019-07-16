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
                <form method="GET"  action="{{ route('hospital-staff.send.password-reset') }}">
                    {{ csrf_field() }}
                    <label for="email">・メールアドレスを入力してください</label>
                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                        <input type="email" class="form-control" id="email" name="email" value="{{ Input::old('email') }}" placeholder="メールアドレスを入力してください">
                        @if ($errors->has('email')) <p class="help-block strong">{{ $errors->first('email') }}</p> @endif
                    </div>
                    
                    <div class="row mt-5">
                      <div class="col-xs-6">
                      </div>
                      <div class="col-xs-3">
                        <a href="/login" class="btn btn-default btn-block btn-flat margin-right-30">戻る</a>
                      </div>
                      <div class="col-xs-3">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">送信</button>
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