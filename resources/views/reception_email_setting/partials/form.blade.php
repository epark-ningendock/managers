<div class="form-entry">
    <div class="box-body">
    <h2>受付メール設定</h2>
    <p class="text-bold">【メール設定】</p>
    <p class='ml-3'>差出人メールアドレス：unei@eparkdock.com</p>

    <div class="form-group @if ($errors->has('reception_email_setting')) has-error @endif">
      <input type="hidden" name="lock_version" value="{{ $reception_email_setting->lock_version or '' }}" />

        <div class="form-group py-sm-2 radio ml-3 hospital_email_reception_flg">
            <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
            <label for="status">受信希望者・院内受付メール送信設定</label>
            <group class="inline-radio two-option">
                <div>
                    {{ Form::radio('in_hospital_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
                    <label>配信可</label>
                    </div>
                <div>
                    {{ Form::radio('in_hospital_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::NOT_ACCEPT ? true : false) }}
                    <label>配信不可</label>
                </div>
            </group>
            @if ($errors->has('in_hospital_email_reception_flg')) <p class="help-block has-error">{{ $errors->first('in_hospital_email_reception_flg') }}</p> @endif
        </div>
    </div>

      <div class='form-group checkbox ml-3 confirmation_email_reception_flag @if ($errors->has('hospital_reception_email_transmission_setting')) has-error @endif'>

          <p>{{ Form::hidden('in_hospital_confirmation_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_confirmation_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_confirmation_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_confirmation_email_reception_flg_01']) }}
              <label for="in_hospital_confirmation_email_reception_flg_01">受付確定時</label>
          </p>

          <p>
              {{ Form::hidden('in_hospital_change_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_change_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_change_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_change_email_reception_flg_01']) }}
              <label for="in_hospital_change_email_reception_flg_01">受付変更時</label>
          </p>

          <p>
              {{ Form::hidden('in_hospital_cancellation_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_cancellation_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_cancellation_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_cancellation_email_reception_flg_01']) }}
              <label for="in_hospital_cancellation_email_reception_flg_01">キャンセル時</label>
          </p>
        @if ($errors->has('hospital_reception_email_transmission_setting')) <p class="help-block">{{ $errors->first('hospital_reception_email_transmission_setting') }}</p> @endif
      </div>

        <div class="form-group @if ($errors->has('reception_email_setting')) has-error @endif">
            <input type="hidden" name="lock_version" value="{{ $reception_email_setting->lock_version or '' }}" />

            <div class="form-group py-sm-2 radio ml-3 hospital_email_reception_flg">
                <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
                <label for="status">受付メール受信アドレス設定</label>
                <group class="inline-radio two-option">
                    <div>
                        {{ Form::radio('email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
                        <label>受取可</label>
                    </div>
                    <div>
                        {{ Form::radio('email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::NOT_ACCEPT ? true : false) }}
                        <label>受取不可</label>
                    </div>
                </group>
                @if ($errors->has('email_reception_flg')) <p class="help-block has-error">{{ $errors->first('email_reception_flg') }}</p> @endif
            </div>
        </div>

      <div class='checkbox ml-3 reception_type_flag'>
          <p>
              {{ Form::hidden('in_hospital_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_reception_email_flg_01']) }}
              <label for="in_hospital_reception_email_flg_01">院内受付</label>
          </p>

          <p>
              {{ Form::hidden('web_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('web_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->web_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'web_reception_email_flg_01']) }}
              <label for="web_reception_email_flg_01">WEB受付</label>
          </p>
      </div>

      <div class="form-group @if ($errors->has('reception_email1')) has-error @endif">
        <label class='ml-3'>受信メールアドレス1</label>
        {{ Form::text('reception_email1' , (old('reception_email1')) ? old('reception_email1') : $reception_email_setting->reception_email1, ['class' => 'form-control ml-3', 'id' => 'reception_email1', 'placeholder' => 'メールアドレスを入力してください']) }}
        @if ($errors->has('reception_email1')) <p class="help-block ml-3">{{ $errors->first('reception_email1') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('reception_email2')) has-error @endif">
        <label class='ml-3'>受信メールアドレス2</label>
        {{ Form::text('reception_email2' , (old('reception_email2')) ? old('reception_email2') : $reception_email_setting->reception_email2, ['class' => 'form-control ml-3', 'id' => 'reception_email2', 'placeholder' => 'メールアドレスを入力してください']) }}
        @if ($errors->has('reception_email2')) <p class="help-block ml-3">{{ $errors->first('reception_email2') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('reception_email3')) has-error @endif">
        <label class='ml-3'>受信メールアドレス3</label>
        {{ Form::text('reception_email3' , (old('reception_email3')) ? old('reception_email3') : $reception_email_setting->reception_email3, ['class' => 'form-control ml-3', 'id' => 'reception_email3', 'placeholder' => 'メールアドレスを入力してください']) }}
        @if ($errors->has('reception_email3')) <p class="help-block ml-3">{{ $errors->first('reception_email3') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('reception_email4')) has-error @endif">
        <label class='ml-3'>受信メールアドレス4</label>
        {{ Form::text('reception_email4' , (old('reception_email4')) ? old('reception_email4') : $reception_email_setting->reception_email4, ['class' => 'form-control ml-3', 'id' => 'reception_email4', 'placeholder' => 'メールアドレスを入力してください']) }}
        @if ($errors->has('reception_email4')) <p class="help-block ml-3">{{ $errors->first('reception_email4') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('reception_email5')) has-error @endif">
        <label class='ml-3'>受信メールアドレス5</label>
        {{ Form::text('reception_email5' , (old('reception_email5')) ? old('reception_email5') : $reception_email_setting->reception_email5, ['class' => 'form-control ml-3', 'id' => 'reception_email5', 'placeholder' => 'メールアドレスを入力してください']) }}
        @if ($errors->has('reception_email5')) <p class="help-block ml-3">{{ $errors->first('reception_email5') }}</p> @endif
      </div>

      <div class="form-group">
          <label class='ml-3'>EPARK人間ドック受付設定</label>
          <div class='checkbox ml-3 epark_in_hospital_reception_mail'>
              <p>
                  {{ Form::hidden('epark_in_hospital_reception_mail_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
                  {{ Form::checkbox('epark_in_hospital_reception_mail_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->epark_in_hospital_reception_mail_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'epark_in_hospital_reception_mail_flg_01']) }}
                  <label for="epark_in_hospital_reception_mail_flg_01">院内受付</label>
              </p>

              <p>
                  {{ Form::hidden('epark_web_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
                  {{ Form::checkbox('epark_web_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->epark_web_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'epark_web_reception_email_flg_01']) }}
                  <label for="epark_web_reception_email_flg_01">WEB受付</label>
              </p>
          </div>
      </div>
    </div>

      <div class="box-footer">
          <button type="submit" class="btn btn-primary">保存</button>
      </div>
</div>

@push('js')
  <script>
      (function ($) {
          /* ---------------------------------------------------
           // hospital email reception flag change
          -----------------------------------------------------*/
          (function () {
              const change = function() {
                  console.log($('.hospital_email_reception_flg input[type=radio]:checked'));
                  console.log($('.hospital_email_reception_flg input[type=radio]'));
                  if ($('.hospital_email_reception_flg input[type=radio]:checked').val() == '0') {
                      $('.confirmation_email_reception_flag input:checkbox').prop('disabled', true);
                  } else {
                      $('.confirmation_email_reception_flag input:checkbox').prop('disabled', false);
                  }
              };
              $('.hospital_email_reception_flg input:radio').change(function() {
                  change();
              })
              change();
          })();
      })(jQuery);
  </script>
@endpush