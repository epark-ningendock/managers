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
    <div class="checkbox mt-0">
      <label><input type="checkbox" id="is_hospital_view" class="permission-check"> 閲覧</label>
      <label class="ml-3"><input type="checkbox" id="is_hospital_edit" class="permission-check"> 編集</label>
    </div>
    @if ($errors->has('is_hospital')) <p class="help-block">{{ $errors->first('is_hospital') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_staff')) has-error @endif">
    <label class="mb-0">スタッフ管理</label>
    <div class="checkbox mt-0">
      <label><input type="checkbox" id="is_staff_view" class="permission-check"> 閲覧</label>
      <label class="ml-3"><input type="checkbox" id="is_staff_edit" class="permission-check"> 編集</label>
    </div>
    @if ($errors->has('is_staff')) <p class="help-block">{{ $errors->first('is_staff') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_item_category')) has-error @endif">
    <label class="mb-0">検査コース分類</label>
    <div class="checkbox mt-0">
      <label><input type="checkbox" id="is_item_category_view" class="permission-check"> 閲覧</label>
      <label class="ml-3"><input type="checkbox" id="is_item_category_edit" class="permission-check"> 編集</label>
    </div>
    @if ($errors->has('is_item_category')) <p
        class="help-block">{{ $errors->first('is_item_category') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_invoice')) has-error @endif">
    <label class="mb-0">請求管理</label>
    <div class="checkbox mt-0">
      <label><input type="checkbox" id="is_invoice_view" class="permission-check"> 閲覧</label>
      <label class="ml-3"><input type="checkbox" id="is_invoice_edit" class="permission-check"> 編集</label>
      <label class="ml-3"><input type="checkbox" id="is_invoice_upload" class="permission-check"> アップロード</label>
    </div>
    @if ($errors->has('is_invoice')) <p class="help-block">{{ $errors->first('is_invoice') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_pre_account')) has-error @endif">
    <label class="mb-0">事前決済管理</label>
    <div class="checkbox mt-0">
      <label><input type="checkbox" id="is_pre_account_view" class="permission-check"> 閲覧</label>
      <label class="ml-3"><input type="checkbox" id="is_pre_account_edit" class="permission-check"> 編集</label>
      <label class="ml-3"><input type="checkbox" id="is_pre_account_upload" class="permission-check"> アップロード</label>
    </div>
    @if ($errors->has('is_pre_account')) <p class="help-block">{{ $errors->first('is_pre_account') }}</p> @endif
  </div>

  <input type="hidden" name="is_hospital" id="is_hospital" class="permission"
         value="{{ old('is_hospital', 0)}}"/>
  <input type="hidden" name="is_staff" id="is_staff" class="permission" value="{{ old('is_staff', 0)}}"/>
  <input type="hidden" name="is_item_category" class="permission" value="{{ old('is_item_category', 0)}}"/>
  <input type="hidden" name="is_invoice" class="permission" value="{{ old('is_invoice', 0)}}"/>
  <input type="hidden" name="is_pre_account" class="permission" value="{{ old('is_pre_account', 0)}}"/>

  <div class="box-footer">
    <a href="{{ url()->previous() }}" class="btn btn-default">バック</a>
    <button type="submit" class="btn btn-primary">つくる</button>
  </div>

</div>
