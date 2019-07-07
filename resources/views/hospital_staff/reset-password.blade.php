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
                <form method="POST"  action="{{ route('hospital-staff.reset.password', ['hospital_staff_id' => $hospital_staff_id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group @if ($errors->has('password')) has-error @endif">
                        <label for="password">新しいパスワード</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                    </div>
                    
                    
                    <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                        <label for="password-confirm">新しいパスワード（確認用）</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                    </div>
                    
                    
                    <div class="box-footer">
                        <a href="{{ url('/staff') }}" class="btn btn-default">戻る</a>
                        <button type="submit" class="btn btn-primary">更新</button>
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
