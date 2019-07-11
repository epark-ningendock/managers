@php
  use App\Enums\StaffStatus;
  use App\Enums\Authority;
  use \App\Enums\Permission;
@endphp
<div class="box-body">
  {!! csrf_field() !!}
  <div class="form-group @if ($errors->has('status')) has-error @endif">

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

  <div class="form-group @if ($errors->has('is_hospital')) has-error @endif">
    <label class="mb-0">医療機関管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" name="is_hospital" id="is_hospital_none" value="{{ Permission::None }}"
               {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::None ? 'checked' : '' }}
               class="permission-check">
        {{ Permission::None()->description }}
      </label>
      <label>
        <input type="radio" name="is_hospital" id="is_hospital_view" value="{{ Permission::View }}"
               {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::View ? 'checked' : '' }}
               class="permission-check">
        {{ Permission::View()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_hospital_edit" name="is_hospital" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::Edit ? 'checked' : '' }}>
        {{ Permission::Edit()->description }}
      </label>
    </div>
    @if ($errors->has('is_hospital')) <p class="help-block">{{ $errors->first('is_hospital') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_staff')) has-error @endif">
    <label class="mb-0">スタッフ管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_staff_none" name="is_staff" class="permission-check" value="{{ Permission::None  }}"
            {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::None ? 'checked' : '' }}>
        {{ Permission::None()->description }}
      </label>
      <label>
        <input type="radio" id="is_staff_view" name="is_staff" class="permission-check" value="{{ Permission::View  }}"
            {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::View ? 'checked' : '' }}>
        {{ Permission::View()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_staff_edit" name="is_staff" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::Edit ? 'checked' : '' }}>
        {{ Permission::Edit()->description }}
      </label>
    </div>
    @if ($errors->has('is_staff')) <p class="help-block">{{ $errors->first('is_staff') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_cource_classification')) has-error @endif">
    <label class="mb-0">検査コース分類</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_cource_classification_none" name="is_cource_classification" value="{{ Permission::None }}" class="permission-check"
            {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::None ? 'checked' : '' }}
        >
        {{ Permission::None()->description }}
      </label>
      <label>
        <input type="radio" id="is_cource_classification_view" name="is_cource_classification" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::View ? 'checked' : '' }}
        >
        {{ Permission::View()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_cource_classification_edit" name="is_cource_classification" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::Edit ? 'checked' : '' }}>
        {{ Permission::Edit()->description }}
      </label>
    </div>
    @if ($errors->has('is_cource_classification')) <p
        class="help-block">{{ $errors->first('is_cource_classification') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_invoice')) has-error @endif">
    <label class="mb-0">請求管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_invoice_none" name="is_invoice" value="{{ Permission::None }}" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::None ? 'checked' : '' }}>
        {{ Permission::None()->description }}
      </label>
      <label>
        <input type="radio" id="is_invoice_view" name="is_invoice" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::View ? 'checked' : '' }}>
        {{ Permission::View()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_invoice_edit" name="is_invoice" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::Edit ? 'checked' : '' }}>
        {{ Permission::Edit()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_invoice_upload" name="is_invoice" value="{{ Permission::Upload }}" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::Upload ? 'checked' : '' }}>
        {{ Permission::Upload()->description }}
      </label>
    </div>
    @if ($errors->has('is_invoice')) <p class="help-block">{{ $errors->first('is_invoice') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_pre_account')) has-error @endif">
    <label class="mb-0">事前決済管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_pre_account_none" name="is_pre_account" value="{{ Permission::None }}" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::None ? 'checked' : '' }}>
        {{ Permission::None()->description }}
      </label>
      <label>
        <input type="radio" id="is_pre_account_view" name="is_pre_account" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::View ? 'checked' : '' }}>
        {{ Permission::View()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_pre_account_edit" name="is_pre_account" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::Edit ? 'checked' : '' }}>
        {{ Permission::Edit()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_pre_account_upload" name="is_pre_account" value="{{ Permission::Upload }}" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::Upload ? 'checked' : '' }}>
        {{ Permission::Upload()->description }}
      </label>
    </div>
    @if ($errors->has('is_pre_account')) <p class="help-block">{{ $errors->first('is_pre_account') }}</p> @endif
  </div>

  <div class="form-group @if ($errors->has('is_contract')) has-error @endif">
    <label class="mb-0">契約管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_contract_none" name="is_contract" value="{{ Permission::None }}" class="permission-check"
            {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::None ? 'checked' : '' }}>
        {{ Permission::None()->description }}
      </label>
      <label>
        <input type="radio" id="is_contract_view" name="is_contract" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::View ? 'checked' : '' }}>
        {{ Permission::View()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_contract_edit" name="is_contract" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::Edit ? 'checked' : '' }}>
        {{ Permission::Edit()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="is_contract_upload" name="is_contract" value="{{ Permission::Upload }}" class="permission-check"
            {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::Upload ? 'checked' : '' }}>
        {{ Permission::Upload()->description }}
      </label>
    </div>
    @if ($errors->has('is_contract')) <p class="help-block">{{ $errors->first('is_contract') }}</p> @endif
  </div>


  <div class="box-footer">
    <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
    <button type="submit" class="btn btn-primary">保存</button>
  </div>

</div>
