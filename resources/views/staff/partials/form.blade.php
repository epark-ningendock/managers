@php
  use App\Enums\StaffStatus;
  use App\Enums\Authority;
  use \App\Enums\Permission;
@endphp
@include('layouts.partials.message')
<div class="box-body">
  {!! csrf_field() !!}
  <div class="form-group">
    <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
    <label for="status">状態</label>
    <div class="radio">
      <label>
        <input type="radio" name="status"
               {{ old('status', (isset($staff) ? $staff->status->value : null) ) == StaffStatus::Valid ? 'checked' : '' }}
               value="{{ StaffStatus::Valid }}">
        {{ StaffStatus::Valid()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" name="status"
               {{ old('status', (isset($staff) ? $staff->status->value : null)) == StaffStatus::Invalid ? 'checked' : '' }}
               value="{{ StaffStatus::Invalid }}">
        {{ StaffStatus::Invalid()->description }}
      </label>
    </div>
    @if ($errors->has('status')) <p class="help-block has-error">{{ $errors->first('status') }}</p> @endif
  </div>

  <label for="name">スタッフ名</label>  
  <div class="form-group @if ($errors->has('name')) has-error @endif">
    <input type="text" class="form-control text" id="name" name="name"
           value="{{ old('name', (isset($staff) ? $staff->name : null)) }}"
           placeholder="スタッフ名">
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
  </div>

  <label for="login_id">ログインID</label>
  <div class="form-group  @if ($errors->has('login_id')) has-error @endif">
    <input type="text" class="form-control text" id="login_id" name="login_id"
           value="{{ old('login_id', (isset($staff) ? $staff->login_id : null)) }}"
           placeholder="ログインID"> 
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  <label for="email">メールアドレス</label>
  <div class="form-group  @if ($errors->has('email')) has-error @endif">
    <input type="email" class="form-control text" id="email" name="email"
           value="{{ old('email', (isset($staff) ? $staff->email : null)) }}"
           placeholder="メールアドレス">
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
  </div>

  @if (!isset($staff))
    <label for="password" class="required">パスワード</label>
    <div class="form-group @if( $errors->has('password'))  has-error @endif">
        <input type="password" class="form-control" id="password" name="password"
               placeholder="パスワード">
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>
    <label for="password-confirm">パスワード（確認用）</label>
    <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
        <input type="password" class="form-control" id="password-confirm" name="password_confirmation"
               placeholder="パスワード（確認用）">
        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
    </div>
  @endif

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

  <label class="mt-3">スタッフ権限</label>
  <div class="form-group mt-3">
    <div class="radio mt-0">
      @if (Auth::user()->authority->value === Authority::Admin)
        <label>
            <input type="radio" id="authority_admin" name="authority" value="{{ Authority::Admin }}" class="permission-check"
                {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::Admin ? 'checked' : '' }}>
            {{ Authority::Admin()->description }}
        </label>
      @endif
      <label class="ml-3">
        <input type="radio" name="authority" id="authority_member" value="{{ Authority::Member }}"
                {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::Member ? 'checked' : '' }}
                class="permission-check">
        {{ Authority::Member()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" name="authority" id="authority_external_staff" value="{{ Authority::ExternalStaff }}"
                {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::ExternalStaff ? 'checked' : '' }}
                class="permission-check">
        {{ Authority::ExternalStaff()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" id="authority_contract_staff" name="authority" value="{{ Authority::ContractStaff }}" class="permission-check"
            {{ old('authority', (isset($staff) ? $staff->authority->value : -1)) == Authority::ContractStaff ? 'checked' : '' }}>
        {{ Authority::ContractStaff()->description }}
      </label>
    </div>
    @if ($errors->has('authority')) <p class="help-block has-error">{{ $errors->first('authority') }}</p> @endif
  </div>


  <div class="form-group">
    <label class="mb-0">医療機関管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" name="is_hospital" id="is_hospital_none" value="{{ Permission::None }}"
               {{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::None ? 'checked' : '' }}
               class="permission-check">
        {{ Permission::None()->description }}
      </label>
      <label class="ml-3">
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
    @if ($errors->has('is_hospital')) <p class="help-block has-error">{{ $errors->first('is_hospital') }}</p> @endif
  </div>

  <div class="form-group">
    <label class="mb-0">スタッフ管理</label>
    <div class="radio mt-0">
      <label>
        <input type="radio" id="is_staff_none" name="is_staff" class="permission-check" value="{{ Permission::None  }}"
            {{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::None ? 'checked' : '' }}>
        {{ Permission::None()->description }}
      </label>
      <label class="ml-3">
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
    @if ($errors->has('is_staff')) <p class="help-block has-error">{{ $errors->first('is_staff') }}</p> @endif
  </div>
  <div id="sample">
    <div class="form-group">
      <label class="mb-0">検査コース分類</label>
      <div class="radio mt-0">
        <label>
          <input type="radio" id="is_cource_classification_none" name="is_cource_classification" value="{{ Permission::None }}" class="permission-check"
              {{ old('is_cource_classification', (isset($staff) ? $staff->staff_auth->is_cource_classification : -1)) == Permission::None ? 'checked' : '' }}
          >
          {{ Permission::None()->description }}
        </label>
        <label class="ml-3">
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
      @if ($errors->has('is_cource_classification')) <p class="help-block has-error">{{ $errors->first('is_cource_classification') }}</p> @endif
    </div>

    <div class="form-group">
      <label class="mb-0">請求管理</label>
      <div class="radio mt-0">
        <label>
          <input type="radio" id="is_invoice_none" name="is_invoice" value="{{ Permission::None }}" class="permission-check"
              {{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::None ? 'checked' : '' }}>
          {{ Permission::None()->description }}
        </label>
        <label class="ml-3">
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
      @if ($errors->has('is_invoice')) <p class="help-block has-error">{{ $errors->first('is_invoice') }}</p> @endif
    </div>

    <div class="form-group">
      <label class="mb-0">事前決済管理</label>
      <div class="radio mt-0">
        <label>
          <input type="radio" id="is_pre_account_none" name="is_pre_account" value="{{ Permission::None }}" class="permission-check"
              {{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::None ? 'checked' : '' }}>
          {{ Permission::None()->description }}
        </label>
        <label class="ml-3">
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
      @if ($errors->has('is_pre_account')) <p class="help-block has-error">{{ $errors->first('is_pre_account') }}</p> @endif
    </div>

    <div class="form-group">
      <label class="mb-0">契約管理</label>
      <div class="radio mt-0">
        <label>
          <input type="radio" id="is_contract_none" name="is_contract" value="{{ Permission::None }}" class="permission-check"
              {{ old('is_contract', (isset($staff) ? $staff->staff_auth->is_contract : -1)) == Permission::None ? 'checked' : '' }}>
          {{ Permission::None()->description }}
        </label>
        <label class="ml-3">
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
      @if ($errors->has('is_contract')) <p class="help-block has-error">{{ $errors->first('is_contract') }}</p> @endif
    </div>
  </div>


  <div class="box-footer">
    <a href="{{ route('staff.index') }}" class="btn btn-default">戻る</a>
    <button type="submit" class="btn btn-primary">保存</button>
  </div>

</div>


@push('js')
    <script type="text/javascript">
      (function ($) {
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
          });

          $('#authority_admin').change(function() {
            if ($('#authority_admin').prop("checked")) {
              resetStaffValue()
            }
          });

          $('#authority_admin').change(function() {
            if ($('#authority_member').prop("checked")) {
              resetStaffValue()
            }
          });

          $('#authority_external_staff').change(function() {
            if ($('#authority_external_staff').prop("checked")) {
              resetStaffValue()
            }
          });

          $('#authority_contract_staff').change(function() {
            if ($('#authority_contract_staff').prop("checked")) {
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
        })();

      })(jQuery);


    </script>

@endpush

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