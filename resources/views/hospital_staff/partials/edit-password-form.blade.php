@include('layouts.partials.message')
@if(isset($hospital_staff) && !$hospital_staff->first_login_at )
    <div class="alert alert-error alert-block alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <strong class="white-space">パスワードが初期発行時から変更されていません。</strong>
    </div>
@endif
<div class="box-body">

    <div class="form-group @if ($errors->has('old_password')) has-error @endif">
        <label for="old_password">現在のパスワード<span class="form_required">必須</span></label>
        <input id="old_password" type="password" class="w16em form-control" name="old_password" required>
        @if ($errors->has('old_password')) <p class="help-block">{{ $errors->first('old_password') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('password')) has-error @endif">
        <label for="password">新しいパスワード<span class="form_required">必須</span></label>
        <input id="password" type="password" class="w16em form-control" name="password" required>
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>


    <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
        <label for="password-confirm">新しいパスワード（確認用）<span class="form_required">必須</span></label>
        <input id="password-confirm" type="password" class="w16em form-control" name="password_confirmation" required>
        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
    </div>

    <div class="box-footer">
        <div class="footer-submit">
            <a href="{{ route('hospital-staff.index') }}" class="btn btn-default">戻る</a>
            <button type="submit" class="btn btn-primary">更新</button>
        </div>
    </div>

</div>