@php
  use App\Enums\StaffStatus;
  use App\Enums\Authority;
  use \App\Enums\Permission;
@endphp
@include('layouts.partials.error_pan')
<div class="box-body staff-form">
  {!! csrf_field() !!}
  <h2>スタッフ情報</h2>
  <div class="form-group py-sm-2">
    <label for="status">状態</label>
    <group class="inline-radio two-option">
      <div>
        <input type="radio" name="status" {{ old('status', (isset($staff) ? $staff->status->value : null) ) == StaffStatus::Valid ? 'checked' : 'checked' }}
        value="{{ StaffStatus::Valid }}"
        ><label>{{ StaffStatus::Valid()->description }}</label></div>
      <div>
        <input type="radio" name="status" {{ old('status', (isset($staff) ? $staff->status->value : null)) == StaffStatus::Invalid ? 'checked' : '' }}
        value="{{ StaffStatus::Invalid }}"><label>{{ StaffStatus::Invalid()->description }}</label></div>
    </group>
    @if ($errors->has('status')) <p class="help-block has-error">{{ $errors->first('status') }}</p> @endif
  </div>

  <div class="form-group py-sm-1 @if ($errors->has('name')) has-error @endif">
    <label for="name">
      スタッフ名
      <span class="form_required">必須</span>
    </label>
    <input type="text" class="form-control text w16em" id="name" name="name"
           value="{{ old('name', (isset($staff) ? $staff->name : null)) }}"
           placeholder="スタッフ名">
    @if ($errors->has('name')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('name') }}</p> @endif
  </div>

  <div class="form-group py-sm-1 @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">ログインID<span class="form_required">必須</span></label>
    <input type="text" class="form-control text w16em" id="login_id" name="login_id"
           value="{{ old('login_id', (isset($staff) ? $staff->login_id : null)) }}"
           placeholder="ログインID"> 
    @if ($errors->has('login_id')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('login_id') }}</p> @endif
  </div>

  <div class="form-group py-sm-1 @if ($errors->has('email')) has-error @endif">
    <label for="email">メールアドレス<span class="form_required">必須</span></label>
    <input type="email" class="form-control text w20em" id="email" name="email"
           value="{{ old('email', (isset($staff) ? $staff->email : null)) }}"
           placeholder="メールアドレス">
    @if ($errors->has('email')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('email') }}</p> @endif
  </div>

  <div class="form-group py-sm-1 @if ($errors->has('department_id')) has-error @endif">
    <label for="department">部署</label>
    <select name="department_id" id="department_id" class="form-control select-box  @if ($errors->has('department_id')) has-error @endif">
        <option value=""></option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}"
                @if(old('department_id', (isset($staff) ? $staff->department_id : null)) == $department->id)
                    selected="selected"
                @endif
            >{{ $department->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('department_id')) <p class="help-block">{{ $errors->first('department_id') }}</p> @endif
  </div>
  <h2>権限</h2>
  <fieldset class="form-group mt-3">
    <legend class="mt-3">スタッフ権限</legend>
      @if (Auth::user()->authority->value === Authority::Admin)
        <div class="radio">
          <input  value="{{ Authority::Admin }}" id="authority_admin" name="authority" type="radio" checked
            class="permission-check"
            {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::Admin ? 'checked' : '' }}
          >
          <label for="authority_admin" class="radio-label">{{ Authority::Admin()->description }}</label>
        </div>
      @endif
      <div class="radio">
        <input type="radio" name="authority" id="authority_member" value="{{ Authority::Member }}"
                {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::Member ? 'checked' : '' }}
                class="permission-check">
        <label for="authority_member" class="radio-label">{{ Authority::Member()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" name="authority" id="authority_external_staff" value="{{ Authority::ExternalStaff }}"
                {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::ExternalStaff ? 'checked' : '' }}
                class="permission-check">
        <label for="authority_external_staff" class="radio-label">{{ Authority::ExternalStaff()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="authority_contract_staff" name="authority" value="{{ Authority::ContractStaff }}" class="permission-check"
            {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::ContractStaff ? 'checked' : '' }}>
        <label for="authority_contract_staff" class="radio-label">{{ Authority::ContractStaff()->description }}</label>
      </div>
    @if ($errors->has('authority')) <p class="help-block has-error">{{ $errors->first('authority') }}</p> @endif
  </fieldset>
  <fieldset class="form-group">
    <legend class="mb-0">医療機関管理</legend>
      <div class="radio">
        <input type="radio" name="is_hospital" id="is_hospital_view" value="{{ Permission::View }}"
               {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::View ? 'checked' : 'checked' }}
               class="permission-check">
        <label for="is_hospital_view" class="radio-label">{{ Permission::View()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_hospital_edit" name="is_hospital" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::Edit ? 'checked' : '' }}>
        <label for="is_hospital_edit" class="radio-label">{{ Permission::Edit()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" name="is_hospital" id="is_hospital_none" value="{{ Permission::None }}"
               {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::None ? 'checked' : '' }}
               class="permission-check">
        <label for="is_hospital_none" class="radio-label">{{ Permission::None()->description }}</label>
      </div>
    @if ($errors->has('is_hospital')) <p class="help-block has-error">{{ $errors->first('is_hospital') }}</p> @endif
  </fieldset>

  <div class="form-group">
    <legend class="mb-0">スタッフ管理</legend>
    <div class="radio">
      <input type="radio" id="is_staff_view" name="is_staff" class="permission-check" value="{{ Permission::View  }}"
              {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::View ? 'checked' : 'checked' }}>
      <label for="is_staff_view" class="radio-label">{{ Permission::View()->description }}</label>
    </div>
    <div class="radio">
      <input type="radio" id="is_staff_edit" name="is_staff" value="{{ Permission::Edit }}" class="permission-check"
              {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::Edit ? 'checked' : '' }}>
      <label for="is_staff_edit" class="radio-label">{{ Permission::Edit()->description }}</label>
    </div>
    <div class="radio">
      <input type="radio" id="is_staff_none" name="is_staff" class="permission-check" value="{{ Permission::None  }}"
              {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::None ? 'checked' : '' }}>
      <label for="is_staff_none" class="radio-label">{{ Permission::None()->description }}</label>
    </div>
    @if ($errors->has('is_staff')) <p class="help-block has-error">{{ $errors->first('is_staff') }}</p> @endif
  </div>

  <fieldset class="form-group">
    <legend class="mb-0">検査コース分類</legend>
      <div class="radio">
        <input type="radio" id="is_cource_classification_view" name="is_cource_classification" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::View ? 'checked' : 'checked' }}
        >
        <label for="is_cource_classification_view" class="radio-label">{{ Permission::View()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_cource_classification_edit" name="is_cource_classification" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::Edit ? 'checked' : '' }}>
        <label for="is_cource_classification_edit" class="radio-label">{{ Permission::Edit()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_cource_classification_none" name="is_cource_classification" value="{{ Permission::None }}" class="permission-check"
                {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::None ? 'checked' : '' }}
        >
        <label for="is_cource_classification_none" class="radio-label">{{ Permission::None()->description }}</label>
      </div>
    @if ($errors->has('is_cource_classification')) <p class="help-block has-error">{{ $errors->first('is_cource_classification') }}</p> @endif
  </fieldset>

  <fieldset class="form-group">
    <legend class="mb-0">請求管理</legend>
      <div class="radio">
        <input type="radio" id="is_invoice_view" name="is_invoice" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::View ? 'checked' : 'checked' }}>
        <label for="is_invoice_view" class="radio-label">{{ Permission::View()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_invoice_edit" name="is_invoice" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::Edit ? 'checked' : '' }}>
        <label for="is_invoice_edit" class="radio-label">{{ Permission::Edit()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_invoice_upload" name="is_invoice" value="{{ Permission::Upload }}" class="permission-check"
            {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::Upload ? 'checked' : '' }}>
        <label for="is_invoice_upload" class="radio-label">{{ Permission::Upload()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_invoice_none" name="is_invoice" value="{{ Permission::None }}" class="permission-check"
                {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::None ? 'checked' : '' }}>
        <label for="is_invoice_none" class="radio-label">{{ Permission::None()->description }}</label>
      </div>
    @if ($errors->has('is_invoice')) <p class="help-block has-error">{{ $errors->first('is_invoice') }}</p> @endif
  </fieldset>

  <fieldset class="form-group">
    <legend class="mb-0">事前決済管理</legend>
      <div class="radio">
        <input type="radio" id="is_pre_account_view" name="is_pre_account" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::View ? 'checked' : 'checked' }}>
        <label for="is_pre_account_view" class="radio-label">{{ Permission::View()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_pre_account_edit" name="is_pre_account" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::Edit ? 'checked' : '' }}>
        <label for="is_pre_account_edit" class="radio-label">{{ Permission::Edit()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_pre_account_upload" name="is_pre_account" value="{{ Permission::Upload }}" class="permission-check"
            {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::Upload ? 'checked' : '' }}>
        <label for="is_pre_account_upload" class="radio-label">{{ Permission::Upload()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_pre_account_none" name="is_pre_account" value="{{ Permission::None }}" class="permission-check"
                {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::None ? 'checked' : '' }}>
        <label for="is_pre_account_none" class="radio-label">{{ Permission::None()->description }}</label>
      </div>
    @if ($errors->has('is_pre_account')) <p class="help-block has-error">{{ $errors->first('is_pre_account') }}</p> @endif
  </fieldset>

  <div class="form-group">
    <legend class="mb-0">契約管理</legend>
      <div class="radio">
        <input type="radio" id="is_contract_view" name="is_contract" value="{{ Permission::View }}" class="permission-check"
            {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::View ? 'checked' : 'checked' }}>
        <label for="is_contract_view" class="radio-label">{{ Permission::View()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_contract_edit" name="is_contract" value="{{ Permission::Edit }}" class="permission-check"
            {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::Edit ? 'checked' : '' }}>
        <label for="is_contract_edit" class="radio-label">{{ Permission::Edit()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_contract_upload" name="is_contract" value="{{ Permission::Upload }}" class="permission-check"
            {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::Upload ? 'checked' : '' }}>
        <label for="is_contract_upload" class="radio-label">{{ Permission::Upload()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_contract_none" name="is_contract" value="{{ Permission::None }}" class="permission-check"
                {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::None ? 'checked' : '' }}>
        <label for="is_contract_none" class="radio-label">{{ Permission::None()->description }}</label>
      </div>
    @if ($errors->has('is_contract')) <p class="help-block has-error">{{ $errors->first('is_contract') }}</p> @endif
  </div>

  <div class="box-footer">
    <div class="footer-submit">
      <a href="{{ route('staff.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">保存</button>
    </div>
  </div>

</div>

<style>
.text-left {
  text-align: center !important;
}

.text {
  width: 600px;
}
.select-box {
  width: 400px;
}
</style>