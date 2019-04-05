<div class="box-body">
  {!! csrf_field() !!}
  <div class="form-group @if ($errors->has('status')) has-error @endif">
    <label for="status">状態</label>
    <div class="radio">
      <label>
        <input type="radio" name="status"
               {{ old('status', (isset($staff) ? $staff->status : null) ) == \App\Enums\Status::Valid()->value ? 'checked' : '' }}
               value="{{ \App\Enums\Status::Valid()->value }}">
        {{ \App\Enums\Status::Valid()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" name="status"
               {{ old('status', (isset($staff) ? $staff->status : null)) == \App\Enums\Status::Invalid()->value ? 'checked' : '' }}
               value="{{ \App\Enums\Status::Invalid()->value }}">
        {{ \App\Enums\Status::Invalid()->description }}
      </label>
    </div>
    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">スタッフ名</label>
    <input type="text" class="form-control" id="name" name="name"
           value="{{ old('name', (isset($staff) ? $staff->name : null)) }}"
           placeholder="スタッフ名">
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">ログインID</label>
    <input type="text" class="form-control" id="login_id" name="login_id"
           value="{{ old('login_id', (isset($staff) ? $staff->login_id : null)) }}"
           placeholder="ログインID">
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="email">メールアドレス</label>
    <input type="email" class="form-control" id="email" name="email"
           value="{{ old('email', (isset($staff) ? $staff->email : null)) }}"
           placeholder="メールアドレス">
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
  </div>

  <!-- showing password field only for create case -->
  @if(!isset($staff))
    <div class="form-group @if ($errors->has('password')) has-error @endif">
      <label for="password">パスワード</label>
      <input type="password" class="form-control" id="password" name="password"
             placeholder="パスワード">
      @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>


    <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
      <label for="password_confirmation">パスワードを認証する</label>
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
             placeholder="パスワードを認証する">
      @if ($errors->has('password_confirmation')) <p
          class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
    </div>
  @endif

  {{--<h5 class="box-title">権限</h5>--}}

  <div class="form-group @if ($errors->has('is_hospital')) has-error @endif">
    <label class="mb-0">医療機関管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" name="is_hospital" id="is_hospital_view" value="1"
               {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : null)) == 1 ? 'checked' : '' }}
               class="permission-check">
        閲覧
      </label>
      <label class="ml-3">
        <input type="radio" id="is_hospital_edit" name="is_hospital" value="3" class="permission-check"
            {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : null)) == 3 ? 'checked' : '' }}>
        編集
      </label>
    </div>
    @if ($errors->has('is_hospital')) <p class="help-block">{{ $errors->first('is_hospital') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_staff')) has-error @endif">
    <label class="mb-0">スタッフ管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_staff_view" name="is_staff" class="permission-check" value="1"
            {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : null)) == 1 ? 'checked' : '' }}>
        閲覧
      </label>
      <label class="ml-3">
        <input type="radio" id="is_staff_edit" name="is_staff" value="3" class="permission-check"
            {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : null)) == 3 ? 'checked' : '' }}>
        編集
      </label>
    </div>
    @if ($errors->has('is_staff')) <p class="help-block">{{ $errors->first('is_staff') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_item_category')) has-error @endif">
    <label class="mb-0">検査コース分類</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_item_category_view" name="is_item_category" value="1" class="permission-check"
            {{ old('is_item_category', (isset($staff) ? $staff->staff_auth->is_item_category : null)) == 1 ? 'checked' : '' }}
        >
        閲覧
      </label>
      <label class="ml-3">
        <input type="radio" id="is_item_category_edit" name="is_item_category" value="3" class="permission-check"
            {{ old('is_item_category', (isset($staff) ? $staff->staff_auth->is_item_category : null)) == 3 ? 'checked' : '' }}>
        編集
      </label>
    </div>
    @if ($errors->has('is_item_category')) <p
        class="help-block">{{ $errors->first('is_item_category') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_invoice')) has-error @endif">
    <label class="mb-0">請求管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_invoice_view" name="is_invoice" value="1" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : null)) == 1 ? 'checked' : '' }}>
        閲覧
      </label>
      <label class="ml-3">
        <input type="radio" id="is_invoice_edit" name="is_invoice" value="3" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : null)) == 3 ? 'checked' : '' }}>
        編集
      </label>
      <label class="ml-3">
        <input type="radio" id="is_invoice_upload" name="is_invoice" value="7" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : null)) == 7 ? 'checked' : '' }}>
        アップロード
      </label>
    </div>
    @if ($errors->has('is_invoice')) <p class="help-block">{{ $errors->first('is_invoice') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_pre_account')) has-error @endif">
    <label class="mb-0">事前決済管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_pre_account_view" name="is_pre_account" value="1" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : null)) == 1 ? 'checked' : '' }}>
        閲覧
      </label>
      <label class="ml-3">
        <input type="radio" id="is_pre_account_edit" name="is_pre_account" value="3" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : null)) == 3 ? 'checked' : '' }}>
        編集
      </label>
      <label class="ml-3">
        <input type="radio" id="is_pre_account_upload" name="is_pre_account" value="7" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : null)) == 1 ? 'checked' : '' }}>
        アップロード
      </label>
    </div>
    @if ($errors->has('is_pre_account')) <p class="help-block">{{ $errors->first('is_pre_account') }}</p> @endif
  </div>


  <div class="box-footer">
    <a href="{{ url()->previous() }}" class="btn btn-default">バック</a>
    <button type="submit" class="btn btn-primary">つくる</button>
  </div>

</div>
