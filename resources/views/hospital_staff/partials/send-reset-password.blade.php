<div class="box-body">

  <div class="form-group @if ($errors->has('email')) has-error @endif">
      <label for="email">メールアドレスを入力してください</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ Input::old('email') }}" placeholder="メールアドレスを入力してください">
      @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
  </div>

  <div class="box-footer">
      <a href="{{ route('hospital-staff.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">送信</button>
  </div>

</div>