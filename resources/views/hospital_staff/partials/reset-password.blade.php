
<div class="box-body">

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

</div>