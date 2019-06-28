<div class="box-body">

  <div class="form-group @if ($errors->has('name')) has-error @endif">
      <label for="name">PV・予約</label>
      <input type="text" class="form-control" id="name" name="name" value="" placeholder="名前を入力">
      @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
      <div class='checkbox ml-3'>
        <label class="ml-3">
            {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
            ピックアップ
        </label>
    </div>
  </div>

  <div class="form-group @if ($errors->has('email')) has-error @endif">
      <label for="email">アクセスについて</label>
      <input type="email" class="form-control" id="email" name="email" value="" placeholder="メールアドレスを入力して">
      @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
      <label for="login_id">クレジットカード対応</label>
      <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
      @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">外国語対応</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">認定施設について</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">女性対応</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">女性対応</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">お子様対応</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">施設について</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">食事について</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">併用施設について</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">周辺施設について</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">プライバシー配慮</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">検索結果</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">フリーエリア</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">検索ワード</label>
    <input type="text" class="form-control" id="login_id" name="login_id" value="" placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="box-footer">
      <a href="{{ route('hospital.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">作成</button>
  </div>

</div>