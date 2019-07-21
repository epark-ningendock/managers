@include('layouts.partials.message')
@if(isset($hospital_staff) && !$hospital_staff->first_login_at )
    <div class="alert alert-error alert-block alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <strong class="white-space">パスワードが初期発行時から変更されていません。</strong>
    </div>
@endif
<div class="box-body">

    <div class="form-group">
        <label for="old_password">現在のパスワード</label>
        <input id="old_password" type="password" class="form-control" name="old_password" required>
    </div>

    <div class="form-group">
        <label for="password">新しいパスワード</label>
        <input id="password" type="password" class="form-control" name="password" required>
    </div>

    <div class="form-group">
        <label for="password-confirm">新しいパスワード（確認用）</label>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
    </div>

  <div class="box-footer">
      <a href="{{ route('hospital-staff.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">保存</button>
  </div>

</div>