@php
  use App\Enums\StaffStatus;
  use App\Enums\Authority;
  use \App\Enums\Permission;
@endphp

@include('layouts.partials.message')
<div class="form-entry">
  <div class="box-body staff-form">
    {!! csrf_field() !!}
    <h2>スタッフ情報</h2>
    <div class="form-group py-sm-2">
      <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
      <label for="status">状態</label>
      <group class="inline-radio two-option">
        <div>
          <input type="radio" name="status" {{ old('status', (isset($staff) ? $staff->status->value : null) ) == StaffStatus::VALID ? 'checked' : 'checked' }}
          value="{{ StaffStatus::VALID }}"
          ><label>{{ StaffStatus::VALID()->description }}</label></div>
        <div>
          <input type="radio" name="status" {{ old('status', (isset($staff) ? $staff->status->value : null)) == StaffStatus::INVALID ? 'checked' : '' }}
          value="{{ StaffStatus::INVALID }}"><label>{{ StaffStatus::INVALID()->description }}</label></div>
      </group>
      @if ($errors->has('status')) <p class="help-block has-error">{{ $errors->first('status') }}</p> @endif
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group py-sm-1 @if ($errors->has('name')) has-error @endif">
          <label for="name">スタッフ名
              <span class="form_required">必須</span>
          </label>
          <input type="text" class="form-control" id="name" name="name"
                 value="{{ old('name', (isset($staff) ? $staff->name : null)) }}"
                 placeholder="スタッフ名">
          @if ($errors->has('name')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('name') }}</p> @endif
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group py-sm-1 @if ($errors->has('login_id')) has-error @endif">
          <label for="login_id">ログインID<span class="form_required">必須</span></label>
          <input type="text" class="form-control text" id="login_id" name="login_id"
                 value="{{ old('login_id', (isset($staff) ? $staff->login_id : null)) }}"
                 placeholder="ログインID">
          @if ($errors->has('login_id')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('login_id') }}</p> @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group py-sm-1 @if ($errors->has('email')) has-error @endif">
          <label for="email">メールアドレス<span class="form_required">必須</span></label>
          <input type="email" class="form-control text" id="email" name="email"
                 value="{{ old('email', (isset($staff) ? $staff->email : null)) }}"
                 placeholder="メールアドレス">
          @if ($errors->has('email')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('email') }}</p> @endif
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group py-sm-1 @if ($errors->has('department_id')) has-error @endif">
          <label for="department">部署</label>
          <select name="department_id" id="department_id" class="form-control select-box">
            <option value=""></option>
            @foreach($departments as $department)
              <option value="{{ $department->id }}"
                      @if(old('department_id', (isset($staff) ? $staff->department_id : null)) == $department->id)
                      selected="selected"
                      @endif
              >{{ $department->name }}</option>
            @endforeach
          </select>
          @if ($errors->has('department_id')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('department_id') }}</p> @endif
        </div>
      </div>
    </div>
    @if (!isset($staff))
      <div class="row">
        <div class="col-md-6">
          <div class="form-group py-sm-1 @if ($errors->has('password')) has-error @endif">
            <label for="password">パスワード<span class="form_required">必須</span></label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="パスワード">
            @if ($errors->has('password')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('password') }}</p> @endif
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group py-sm-1 @if ($errors->has('password_confirmation')) has-error @endif">
            <label for="password-confirm">パスワード（確認用）<span class="form_required">必須</span></label>
            <input type="password" class="form-control" id="password-confirm" name="password_confirmation"
                   placeholder="パスワード">
            @if ($errors->has('password_confirmation')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('password_confirmation') }}</p> @endif
          </div>
        </div>
      </div>
    @endif

    <h2>権限</h2>
    <fieldset class="form-group mt-3">
      <legend class="mt-3">スタッフ権限</legend>
      <div class="radio mt-0">
        @if (Auth::user()->authority->value === Authority::ADMIN)
          <div class="radio">
            <input  value="{{ Authority::ADMIN }}" id="authority_admin" name="authority" type="radio" checked
              class="permission-check"
              {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::ADMIN ? 'checked' : '' }}
            >
            <label for="authority_admin" class="radio-label">{{ Authority::ADMIN()->description }}</label>
          </div>
        @endif
        <div class="radio">
          <input type="radio" name="authority" id="authority_member" value="{{ Authority::MEMBER }}"
                  {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::MEMBER ? 'checked' : '' }}
                  class="permission-check">
          <label for="authority_member" class="radio-label">{{ Authority::MEMBER()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" name="authority" id="authority_external_staff" value="{{ Authority::EXTERNAL_STAFF }}"
                  {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::EXTERNAL_STAFF ? 'checked' : '' }}
                  class="permission-check">
          <label for="authority_external_staff" class="radio-label">{{ Authority::EXTERNAL_STAFF()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="authority_contract_staff" name="authority" value="{{ Authority::CONTRACT_STAFF }}" class="permission-check"
              {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::CONTRACT_STAFF ? 'checked' : '' }}>
          <label for="authority_contract_staff" class="radio-label">{{ Authority::CONTRACT_STAFF()->description }}</label>
        </div>
      @if ($errors->has('authority')) <p class="help-block has-error">{{ $errors->first('authority') }}</p> @endif
    </fieldset>
    <fieldset class="form-group">
      <legend class="mb-0">医療機関管理</legend>
        <div class="radio">
          <input type="radio" name="is_hospital" id="is_hospital_view" value="{{ Permission::VIEW }}"
                 {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::VIEW ? 'checked' : 'checked' }}
                 class="permission-check">
          <label for="is_hospital_view" class="radio-label">{{ Permission::VIEW()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_hospital_edit" name="is_hospital" value="{{ Permission::EDIT }}" class="permission-check"
              {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::EDIT ? 'checked' : '' }}>
          <label for="is_hospital_edit" class="radio-label">{{ Permission::EDIT()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" name="is_hospital" id="is_hospital_none" value="{{ Permission::NONE }}"
                 {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::NONE ? 'checked' : '' }}
                 class="permission-check">
          <label for="is_hospital_none" class="radio-label">{{ Permission::NONE()->description }}</label>
        </div>
      @if ($errors->has('is_hospital')) <p class="help-block has-error">{{ $errors->first('is_hospital') }}</p> @endif
    </fieldset>

    <div class="form-group">
      <legend class="mb-0">スタッフ管理</legend>
      <div class="radio">
        <input type="radio" id="is_staff_view" name="is_staff" class="permission-check" value="{{ Permission::VIEW  }}"
                {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::VIEW ? 'checked' : 'checked' }}>
        <label for="is_staff_view" class="radio-label">{{ Permission::VIEW()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_staff_edit" name="is_staff" value="{{ Permission::EDIT }}" class="permission-check"
                {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::EDIT ? 'checked' : '' }}>
        <label for="is_staff_edit" class="radio-label">{{ Permission::EDIT()->description }}</label>
      </div>
      <div class="radio">
        <input type="radio" id="is_staff_none" name="is_staff" class="permission-check" value="{{ Permission::NONE  }}"
                {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::NONE ? 'checked' : '' }}>
        <label for="is_staff_none" class="radio-label">{{ Permission::NONE()->description }}</label>
      </div>
      @if ($errors->has('is_staff')) <p class="help-block has-error">{{ $errors->first('is_staff') }}</p> @endif
    </div>
    <fieldset class="form-group">
      <legend class="mb-0">検査コース分類</legend>
        <div class="radio">
          <input type="radio" id="is_cource_classification_view" name="is_cource_classification" value="{{ Permission::VIEW }}" class="permission-check"
              {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::VIEW ? 'checked' : 'checked' }}
          >
          <label for="is_cource_classification_view" class="radio-label">{{ Permission::VIEW()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_cource_classification_edit" name="is_cource_classification" value="{{ Permission::EDIT }}" class="permission-check"
              {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::EDIT ? 'checked' : '' }}>
          <label for="is_cource_classification_edit" class="radio-label">{{ Permission::EDIT()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_cource_classification_none" name="is_cource_classification" value="{{ Permission::NONE }}" class="permission-check"
                  {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::NONE ? 'checked' : '' }}
          >
          <label for="is_cource_classification_none" class="radio-label">{{ Permission::NONE()->description }}</label>
        </div>
      @if ($errors->has('is_cource_classification')) <p class="help-block has-error">{{ $errors->first('is_cource_classification') }}</p> @endif
    </fieldset>

    <fieldset class="form-group">
      <legend class="mb-0">請求管理</legend>
        <div class="radio">
          <input type="radio" id="is_invoice_view" name="is_invoice" value="{{ Permission::VIEW }}" class="permission-check"
              {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::VIEW ? 'checked' : 'checked' }}>
          <label for="is_invoice_view" class="radio-label">{{ Permission::VIEW()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_invoice_edit" name="is_invoice" value="{{ Permission::EDIT }}" class="permission-check"
              {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::EDIT ? 'checked' : '' }}>
          <label for="is_invoice_edit" class="radio-label">{{ Permission::EDIT()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_invoice_upload" name="is_invoice" value="{{ Permission::UPLOAD }}" class="permission-check"
              {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::UPLOAD ? 'checked' : '' }}>
          <label for="is_invoice_upload" class="radio-label">{{ Permission::UPLOAD()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_invoice_none" name="is_invoice" value="{{ Permission::NONE }}" class="permission-check"
                  {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::NONE ? 'checked' : '' }}>
          <label for="is_invoice_none" class="radio-label">{{ Permission::NONE()->description }}</label>
        </div>
      @if ($errors->has('is_invoice')) <p class="help-block has-error">{{ $errors->first('is_invoice') }}</p> @endif
    </fieldset>

    <fieldset class="form-group">
      <legend class="mb-0">事前決済管理</legend>
        <div class="radio">
          <input type="radio" id="is_pre_account_view" name="is_pre_account" value="{{ Permission::VIEW }}" class="permission-check"
              {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::VIEW ? 'checked' : 'checked' }}>
          <label for="is_pre_account_view" class="radio-label">{{ Permission::VIEW()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_pre_account_edit" name="is_pre_account" value="{{ Permission::EDIT }}" class="permission-check"
              {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::EDIT ? 'checked' : '' }}>
          <label for="is_pre_account_edit" class="radio-label">{{ Permission::EDIT()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_pre_account_upload" name="is_pre_account" value="{{ Permission::UPLOAD }}" class="permission-check"
              {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::UPLOAD ? 'checked' : '' }}>
          <label for="is_pre_account_upload" class="radio-label">{{ Permission::UPLOAD()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_pre_account_none" name="is_pre_account" value="{{ Permission::NONE }}" class="permission-check"
                  {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::NONE ? 'checked' : '' }}>
          <label for="is_pre_account_none" class="radio-label">{{ Permission::NONE()->description }}</label>
        </div>
      @if ($errors->has('is_pre_account')) <p class="help-block has-error">{{ $errors->first('is_pre_account') }}</p> @endif
    </fieldset>

    <div class="form-group">
      <legend class="mb-0">契約管理</legend>
        <div class="radio">
          <input type="radio" id="is_contract_view" name="is_contract" value="{{ Permission::VIEW }}" class="permission-check"
              {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::VIEW ? 'checked' : 'checked' }}>
          <label for="is_contract_view" class="radio-label">{{ Permission::VIEW()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_contract_edit" name="is_contract" value="{{ Permission::EDIT }}" class="permission-check"
              {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::EDIT ? 'checked' : '' }}>
          <label for="is_contract_edit" class="radio-label">{{ Permission::EDIT()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_contract_upload" name="is_contract" value="{{ Permission::UPLOAD }}" class="permission-check"
              {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::UPLOAD ? 'checked' : '' }}>
          <label for="is_contract_upload" class="radio-label">{{ Permission::UPLOAD()->description }}</label>
        </div>
        <div class="radio">
          <input type="radio" id="is_contract_none" name="is_contract" value="{{ Permission::NONE }}" class="permission-check"
                  {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::NONE ? 'checked' : '' }}>
          <label for="is_contract_none" class="radio-label">{{ Permission::NONE()->description }}</label>
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
</div>


@push('js')
    <script type="text/javascript">
      (function ($) {
        let data = {};
        let fromAuthorityContractStaff = false;

        (function () {
          $(document).ready( function(){
            if ($('#authority_admin').prop("checked")) {
              resetStaffValue()
            }
 
            if ($('#authority_member').prop("checked")) {
              resetStaffValue()
            }

            if ($('#authority_external_staff').prop("checked")) {
              resetStaffValue()
            }

            if ($('#authority_contract_staff').prop("checked")) {
              resetContractStaffValue()
            }
            data = saveData()
            fromAuthorityContractStaff = $('#authority_contract_staff').prop("checked")
          });
          
          $('#authority_admin').change(function() {
            if ($('#authority_admin').prop("checked")) {
              if (fromAuthorityContractStaff) {
                assignData()
              }
              fromAuthorityContractStaff = false
              resetStaffValue()
            }
          });

          $('#authority_member').change(function() {
            if ($('#authority_member').prop("checked")) {
              if (fromAuthorityContractStaff) {
                assignData()
              }
              fromAuthorityContractStaff = false
              resetStaffValue()
            }
          });

          $('#authority_external_staff').change(function() {
            if ($('#authority_external_staff').prop("checked")) {
              if (fromAuthorityContractStaff) {
                assignData()
              }
              fromAuthorityContractStaff = false
              resetStaffValue()
            }
          });

          $('#authority_contract_staff').change(function() {
            if ($('#authority_contract_staff').prop("checked")) {
              data = saveData()
              fromAuthorityContractStaff = true
              resetContractStaffValue()
            }
          });

          function resetStaffValue() {
            $('#is_contract_none').prop('checked', true);

            $('#is_hospital_none').prop('disabled', false);
            $('#is_hospital_view').prop('disabled', false);
            $('#is_hospital_edit').prop('disabled', false);
            $('#is_staff_none').prop('disabled', false);
            $('#is_staff_view').prop('disabled', false);
            $('#is_staff_edit').prop('disabled', false);
            $('#is_cource_classification_none').prop('disabled', false);
            $('#is_cource_classification_view').prop('disabled', false);
            $('#is_cource_classification_edit').prop('disabled', false);
            $('#is_invoice_none').prop('disabled', false);
            $('#is_invoice_view').prop('disabled', false);
            $('#is_invoice_edit').prop('disabled', false);
            $('#is_invoice_upload').prop('disabled', false);
            $('#is_pre_account_none').prop('disabled', false);
            $('#is_pre_account_view').prop('disabled', false);
            $('#is_pre_account_edit').prop('disabled', false);
            $('#is_pre_account_upload').prop('disabled', false);
            $('#is_contract_none').prop('disabled', true);
            $('#is_contract_view').prop('disabled', true);
            $('#is_contract_edit').prop('disabled', true);
            $('#is_contract_upload').prop('disabled', true);
          }

          function resetContractStaffValue() {
            $('#is_hospital_none').prop("checked", true);
            $('#is_staff_none').prop("checked", true);
            $('#is_cource_classification_none').prop("checked", true);
            $('#is_invoice_none').prop("checked", true);
            $('#is_pre_account_none').prop("checked", true);
            $('#is_contract_upload').prop("checked", true);

            $('#is_hospital_none').prop('disabled', true);
            $('#is_hospital_view').prop('disabled', true);
            $('#is_hospital_edit').prop('disabled', true);
            $('#is_staff_none').prop('disabled', true);
            $('#is_staff_view').prop('disabled', true);
            $('#is_staff_edit').prop('disabled', true);
            $('#is_cource_classification_none').prop('disabled', true);
            $('#is_cource_classification_view').prop('disabled', true);
            $('#is_cource_classification_edit').prop('disabled', true);
            $('#is_invoice_none').prop('disabled', true);
            $('#is_invoice_view').prop('disabled', true);
            $('#is_invoice_edit').prop('disabled', true);
            $('#is_invoice_upload').prop('disabled', true);
            $('#is_pre_account_none').prop('disabled', true);
            $('#is_pre_account_view').prop('disabled', true);
            $('#is_pre_account_edit').prop('disabled', true);
            $('#is_pre_account_upload').prop('disabled', true);
            $('#is_contract_none').prop('disabled', true);
            $('#is_contract_view').prop('disabled', true);
            $('#is_contract_edit').prop('disabled', true);
            $('#is_contract_upload').prop('disabled', true);
          }

          function saveData() {
            let data = {
              is_hospital_none: $('#is_hospital_none').prop('checked'),
              is_hospital_view: $('#is_hospital_view').prop('checked'),
              is_hospital_edit: $('#is_hospital_edit').prop('checked'),
              is_staff_none: $('#is_staff_none').prop('checked'),
              is_staff_view: $('#is_staff_view').prop('checked'),
              is_staff_edit: $('#is_staff_edit').prop('checked'),
              is_cource_classification_none: $('#is_cource_classification_none').prop('checked'),
              is_cource_classification_view: $('#is_cource_classification_view').prop('checked'),
              is_cource_classification_edit: $('#is_cource_classification_edit').prop('checked'),
              is_invoice_none: $('#is_invoice_none').prop('checked'),
              is_invoice_view: $('#is_invoice_view').prop('checked'),
              is_invoice_edit: $('#is_invoice_edit').prop('checked'),
              is_invoice_upload: $('#is_invoice_upload').prop('checked'),
              is_pre_account_none: $('#is_pre_account_none').prop('checked'),
              is_pre_account_view: $('#is_pre_account_view').prop('checked'),
              is_pre_account_edit: $('#is_pre_account_edit').prop('checked'),
              is_pre_account_upload: $('#is_pre_account_upload').prop('checked'),
              is_contract_none: $('#is_contract_none').prop('checked'),
              is_contract_view: $('#is_contract_view').prop('checked'),
              is_contract_edit: $('#is_contract_edit').prop('checked'),
              is_contract_upload: $('#is_contract_upload').prop('checked')
            }
            return data;
          }

          function assignData() {
            $('#is_hospital_none').prop('checked', data.is_hospital_none)
            $('#is_hospital_view').prop('checked', data.is_hospital_view)
            $('#is_hospital_edit').prop('checked', data.is_hospital_edit)
            $('#is_staff_none').prop('checked', data.is_staff_none)
            $('#is_staff_view').prop('checked', data.is_staff_view)
            $('#is_staff_edit').prop('checked', data.is_staff_edit)
            $('#is_cource_classification_none').prop('checked', data.is_cource_classification_none)
            $('#is_cource_classification_view').prop('checked', data.is_cource_classification_view)
            $('#is_cource_classification_edit').prop('checked', data.is_cource_classification_edit)
            $('#is_invoice_none').prop('checked', data.is_invoice_none)
            $('#is_invoice_view').prop('checked', data.is_invoice_view)
            $('#is_invoice_edit').prop('checked', data.is_invoice_edit)
            $('#is_invoice_upload').prop('checked', data.is_invoice_upload)
            $('#is_pre_account_none').prop('checked', data.is_pre_account_none)
            $('#is_pre_account_view').prop('checked', data.is_pre_account_view)
            $('#is_pre_account_edit').prop('checked', data.is_pre_account_edit)
            $('#is_pre_account_upload').prop('checked', data.is_pre_account_upload)
            $('#is_contract_none').prop('checked', data.is_contract_none)
            $('#is_contract_view').prop('checked', data.is_contract_view)
            $('#is_contract_edit').prop('checked', data.is_contract_edit)
            $('#is_contract_upload').prop('checked', data.is_contract_upload)
          }

        })();

      })(jQuery);


    </script>

@endpush