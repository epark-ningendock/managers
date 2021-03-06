@include('layouts.partials.error_pan')
<div class="form-entry" id="hospital_email_setting">
    <div class="box-body">
    <h2>受付メール設定</h2>
    <fieldset class="form-group mt-3">
        <legend>メール設定</legend>
    <p class="sender-email">差出人メールアドレス：{{ env('EPARK_EMAIL_ADDRESS') }}</p>
    <div class="form-group @if ($errors->has('hospital_email_setting')) has-error @endif">
      <input type="hidden" name="lock_version" value="{{ $hospital_email_setting->lock_version or '' }}" />

        <div class="form-group py-sm-2 in_hospital_email_reception_flg">
            <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
            <label for="status">受診希望者・院内受付メール送信設定</label>
            <group class="inline-radio two-option-large" style="width: 350px;">
                <div>
                    <input type="radio" name="in_hospital_email_reception_flg" id="in_hospital_email_reception_flg_true"
                    {{ old('in_hospital_email_reception_flg', (isset($hospital_email_setting) ? $hospital_email_setting->in_hospital_email_reception_flg : null) ) == \App\Enums\ReceptionEmailSetting::ACCEPT ? 'checked' : '' }}
                    value="{{ \App\Enums\ReceptionEmailSetting::ACCEPT }}">
                    <label for="in_hospital_email_reception_flg_true">メール配信を希望する</label>
                </div>
                <div>
                    <input type="radio" name="in_hospital_email_reception_flg" id="in_hospital_email_reception_flg_false"
                    {{ old('in_hospital_email_reception_flg', (isset($hospital_email_setting) ? $hospital_email_setting->in_hospital_email_reception_flg : null) ) == \App\Enums\ReceptionEmailSetting::NOT_ACCEPT ? 'checked' : '' }}
                    value="{{ \App\Enums\ReceptionEmailSetting::NOT_ACCEPT }}">
                    <label id="in_hospital_email_reception_flg_false">メール配信を希望しない</label>
                </div>
            </group>
        </div>
    </div>

      <div class='form-group checkbox ml-3 confirmation_email_reception_flag @if ($errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') != '受信メールアドレスを1つ以上入力してください。')) has-error @endif'>

          <p>{{ Form::hidden('in_hospital_confirmation_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_confirmation_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (old('in_hospital_confirmation_email_reception_flg')) ? old('in_hospital_confirmation_email_reception_flg') : (isset($hospital_email_setting) ? $hospital_email_setting->in_hospital_confirmation_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_confirmation_email_reception_flg_01']) }}
              <label for="in_hospital_confirmation_email_reception_flg_01">受付確定時</label>
          </p>

          <p>
              {{ Form::hidden('in_hospital_change_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_change_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT,  (old('in_hospital_change_email_reception_flg')) ? old('in_hospital_change_email_reception_flg') : (isset($hospital_email_setting) ? $hospital_email_setting->in_hospital_change_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_change_email_reception_flg_01']) }}
              <label for="in_hospital_change_email_reception_flg_01">受付変更時</label>
          </p>

          <p>
              {{ Form::hidden('in_hospital_cancellation_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_cancellation_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (old('in_hospital_cancellation_email_reception_flg')) ? old('in_hospital_cancellation_email_reception_flg') : (isset($hospital_email_setting) ? $hospital_email_setting->in_hospital_cancellation_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_cancellation_email_reception_flg_01']) }}
              <label for="in_hospital_cancellation_email_reception_flg_01">受付キャンセル時</label>
          </p>
        @if ($errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') != '受信メールアドレスを1つ以上入力してください。')) <p class="help-block">{{ $errors->first('in_hospital_email_reception_flg') }}</p> @endif
      </div>

        <div class="form-group @if ($errors->has('hospital_email_setting')) has-error @endif">
            <input type="hidden" name="lock_version" value="{{ $hospital_email_setting->lock_version or '' }}" />

            <div class="form-group py-sm-2 email_reception_flg">
                <input type="hidden" name="updated_at" value="{{ isset($staff) ? $staff->updated_at : null }}">
                <label for="status">受付メール受信アドレス設定</label>
                <group class="inline-radio two-option-middle" style="width: 350px;">
                    <div>
                            <input type="radio" name="email_reception_flg" id="email_reception_flg_true"
                            {{ old('email_reception_flg', (isset($hospital_email_setting) ? $hospital_email_setting->email_reception_flg : null) ) == \App\Enums\ReceptionEmailSetting::ACCEPT ? 'checked' : '' }}
                            value="{{ \App\Enums\ReceptionEmailSetting::ACCEPT }}">
                        <label for="email_reception_flg_true">受け取る</label>
                    </div>
                    <div>
                            <input type="radio" name="email_reception_flg" id="email_reception_flg_false"
                            {{ old('email_reception_flg', (isset($hospital_email_setting) ? $hospital_email_setting->email_reception_flg : null) ) == \App\Enums\ReceptionEmailSetting::NOT_ACCEPT ? 'checked' : '' }}
                            value="{{ \App\Enums\ReceptionEmailSetting::NOT_ACCEPT }}">
                        <label for="email_reception_flg_false">受け取らない</label>
                    </div>
                </group>
            </div>
        </div>

      <div class='form-group checkbox ml-3 reception_type_flag @if ($errors->has('email_reception_flg')) has-error @endif'>
          <p>
              {{ Form::hidden('in_hospital_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('in_hospital_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (old('in_hospital_reception_email_flg')) ? old('in_hospital_reception_email_flg') : (isset($hospital_email_setting) ? $hospital_email_setting->in_hospital_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'in_hospital_reception_email_flg_01']) }}
              <label for="in_hospital_reception_email_flg_01">院内受付</label>
          </p>

          <p>
              {{ Form::hidden('web_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
              {{ Form::checkbox('web_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (old('web_reception_email_flg')) ? old('web_reception_email_flg') : (isset($hospital_email_setting) ? $hospital_email_setting->web_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'web_reception_email_flg_01']) }}
              <label for="web_reception_email_flg_01">WEB受付</label>
          </p>
          @if ($errors->has('email_reception_flg')) <p class="help-block">{{ $errors->first('email_reception_flg') }}</p> @endif
      </div>
      <div class='form-group @if ($errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') === '受信メールアドレスを1つ以上入力してください。')) has-error @endif'>
        <div class="form-group @if ($errors->has('reception_email1')) has-error @endif">
            <label class='ml-3'>受信メールアドレス1</label>
            {{ Form::text('reception_email1' , 
                (old('reception_email1') || $errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') === '受信メールアドレスを1つ以上入力してください。')) ? old('reception_email1') : $hospital_email_setting->reception_email1,
                ['class' => 'form-control ml-3', 'id' => 'reception_email1', 'placeholder' => 'メールアドレスを入力してください']) }}
            @if ($errors->has('reception_email1')) <p class="help-block ml-3">{{ $errors->first('reception_email1') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('reception_email2')) has-error @endif">
            <label class='ml-3'>受信メールアドレス2</label>
            {{ Form::text('reception_email2' ,
                (old('reception_email2') || $errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') === '受信メールアドレスを1つ以上入力してください。')) ? old('reception_email2') : $hospital_email_setting->reception_email2, 
                ['class' => 'form-control ml-3', 'id' => 'reception_email2', 'placeholder' => 'メールアドレスを入力してください']) }}
            @if ($errors->has('reception_email2')) <p class="help-block ml-3">{{ $errors->first('reception_email2') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('reception_email3')) has-error @endif">
            <label class='ml-3'>受信メールアドレス3</label>
            {{ Form::text('reception_email3' , 
                (old('reception_email3') || $errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') === '受信メールアドレスを1つ以上入力してください。')) ? old('reception_email3') : $hospital_email_setting->reception_email3, 
                ['class' => 'form-control ml-3', 'id' => 'reception_email3', 'placeholder' => 'メールアドレスを入力してください']) }}
            @if ($errors->has('reception_email3')) <p class="help-block ml-3">{{ $errors->first('reception_email3') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('reception_email4')) has-error @endif">
            <label class='ml-3'>受信メールアドレス4</label>
            {{ Form::text('reception_email4', 
                (old('reception_email4') || $errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') === '受信メールアドレスを1つ以上入力してください。')) ? old('reception_email4') : $hospital_email_setting->reception_email4, 
                ['class' => 'form-control ml-3', 'id' => 'reception_email4', 'placeholder' => 'メールアドレスを入力してください']) }}
            @if ($errors->has('reception_email4')) <p class="help-block ml-3">{{ $errors->first('reception_email4') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('reception_email5')) has-error @endif">
            <label class='ml-3'>受信メールアドレス5</label>
            {{ Form::text('reception_email5', 
                (old('reception_email5') || $errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') === '受信メールアドレスを1つ以上入力してください。')) ? old('reception_email5') : $hospital_email_setting->reception_email5, 
                ['class' => 'form-control ml-3', 'id' => 'reception_email5', 'placeholder' => 'メールアドレスを入力してください']) }}
            @if ($errors->has('reception_email5')) <p class="help-block ml-3">{{ $errors->first('reception_email5') }}</p> @endif
        </div>
        @if ($errors->has('in_hospital_email_reception_flg') && ($errors->first('in_hospital_email_reception_flg') === '受信メールアドレスを1つ以上入力してください。') ) <p class="help-block">{{ $errors->first('in_hospital_email_reception_flg') }}</p> @endif
      </div>

      <div class="form-group">
          <label class='ml-3'>EPARK人間ドック受付設定</label>
          <div class='checkbox ml-3 epark_in_hospital_reception_mail'>
              <p>
                  {{ Form::hidden('epark_in_hospital_reception_mail_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
                  {{ Form::checkbox('epark_in_hospital_reception_mail_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (old('epark_in_hospital_reception_mail_flg')) ? old('epark_in_hospital_reception_mail_flg') : (isset($hospital_email_setting) ? $hospital_email_setting->epark_in_hospital_reception_mail_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'epark_in_hospital_reception_mail_flg_01']) }}
                  <label for="epark_in_hospital_reception_mail_flg_01">院内受付</label>
              </p>

              <p>
                  {{ Form::hidden('epark_web_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
                  {{ Form::checkbox('epark_web_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (old('epark_web_reception_email_flg')) ? old('epark_web_reception_email_flg') : (isset($hospital_email_setting) ? $hospital_email_setting->epark_web_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false, ['id' => 'epark_web_reception_email_flg_01']) }}
                  <label for="epark_web_reception_email_flg_01">WEB受付</label>
              </p>
          </div>
      </div>
    </fieldset>

        <h2>請求メール設定</h2>

        <div class="form-group py-sm-2">
            <label for="status">{{ trans('messages.billing_email_flg') }}</label>
            <group class="inline-radio two-option" style="width: 190px;">
                <div>
                    <input class="billing_email_flg" type="radio" name="billing_email_flg" @if( ( old('billing_email_flg', $hospital_email_setting->billing_email_flg ?? '' ) == 1 ) || is_null($hospital_email_setting->billing_email_flg) ) checked @endif }}
                    value="1"
                    ><label>{{ trans('messages.billing_email_flg_receive') }}</label></div>
                <div>
                    <input class="billing_email_flg" type="radio" name="billing_email_flg" @if( old('billing_email_flg', $hospital_email_setting->billing_email_flg ?? '') == 0 ) checked @endif
                    value="0"><label>{{ trans('messages.billing_email_flg_not_accept') }}</label></div>
            </group>
        </div>


        <div class="bill-mail-setup bms">

            <div class="bill-fields-box">

                <div class="form-group @if ($errors->has('billing_email1')) has-error @endif">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="billing_email1">{{ trans('messages.billing_email1') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="billing_email1" name="billing_email1" value="{{ old('billing_email1', $hospital_email_setting->billing_email1 ?? '') }}" />
                            @if ($errors->has('billing_email1')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email1') }}</p> @endif
                        </div>
                    </div>
                </div>

                <div class="form-group @if ($errors->has('billing_email2')) has-error @endif">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="billing_email2">{{ trans('messages.billing_email2') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="billing_email2" name="billing_email2" value="{{ old('billing_email2', $hospital_email_setting->billing_email2 ?? '') }}" />
                            @if ($errors->has('billing_email2')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email2') }}</p> @endif
                        </div>
                    </div>
                </div>


                <div class="form-group @if ($errors->has('billing_email3')) has-error @endif">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="billing_email3">{{ trans('messages.billing_email3') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="billing_email3" name="billing_email3" value="{{ old('billing_email3', $hospital_email_setting->billing_email3 ?? '') }}" />
                            @if ($errors->has('billing_email3')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email3') }}</p> @endif
                        </div>
                    </div>
                </div>

                <div class="form-group @if ($errors->has('billing_email4')) has-error @endif">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="billing_email4">{{ trans('messages.billing_email4') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="billing_email4" name="billing_email4" value="{{ old('billing_email4', $hospital_email_setting->billing_email4 ?? '') }}" />
                            @if ($errors->has('billing_email4')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email4') }}</p> @endif
                        </div>
                    </div>
                </div>

                <div class="form-group @if ($errors->has('billing_fax_number') || ($errors->has('billing_email_flg') && ($errors->first('billing_email_flg') !== '請求メールの設定は、必ず指定してください。'))) has-error @endif">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="billing_fax_number">{{ trans('messages.billing_fax_number') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="billing_fax_number" name="billing_fax_number" value="{{ old('billing_fax_number', $hospital_email_setting->billing_fax_number ?? '') }}" />
                            @if ($errors->has('billing_fax_number')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_fax_number') }}</p> @endif
                            <br/>
                            @if ($errors->has('billing_email_flg') && ($errors->first('billing_email_flg') !== '請求メールの設定は、必ず指定してください。')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email_flg') }}</p> @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

      <div class="box-footer">
          <button type="submit" class="btn btn-primary">保存</button>
      </div>
</div>

@push('js')
  <script>
    $(document).ready(function(){
        $('.in_hospital_confirmation_email_reception_flg').on('change', function(){
            return false
        });
    });
      let billingData = {};
      
      function saveBillingData() {
        billingData = {
            billing_email1: $('#billing_email1').val(),
            billing_email2: $('#billing_email2').val(),
            billing_email3: $('#billing_email3').val(),
            billing_email4: $('#billing_email4').val(),
            billing_fax_number: $('#billing_fax_number').val(),
        }
      }

      function setBillingData() {
            $('#billing_email1').val(billingData.billing_email1)
            $('#billing_email2').val(billingData.billing_email2)
            $('#billing_email3').val(billingData.billing_email3)
            $('#billing_email4').val(billingData.billing_email4)
            $('#billing_fax_number').val(billingData.billing_fax_number)
      }

      function clearBillingData() {
            $('#billing_email1').val('')
            $('#billing_email2').val('')
            $('#billing_email3').val('')
            $('#billing_email4').val('')
            $('#billing_fax_number').val('')
      }

      const inputs = $('.bill-fields-box input');
      $(document).ready(function($){
          saveBillingData()
          if ( $("input[name='billing_email_flg']:checked").val() == 1) {
              inputs.each(function(){
                  $(this).attr('disabled', false);
              });
          } else {
              clearBillingData()
              inputs.each(function(){
                  $(this).attr('disabled', true);
              });
          }
      });

      (function ($) {

          /* ---------------------------------------------------
           // Billing mail toggle
          -----------------------------------------------------*/
          $(document).on('change', '.billing_email_flg', function(){
              if ( $(this).val() == 1) {
                  setBillingData()
                  inputs.each(function(){
                      $(this).attr('disabled', false);
                  });
              } else {
                  saveBillingData()
                  clearBillingData()
                  inputs.each(function(){
                      $(this).attr('disabled', true);
                  });
              }
          });


          /* ---------------------------------------------------
           // 受診希望者・院内受付メール送信設定
          -----------------------------------------------------*/
          (function () {
              const change = function() {
                  if ($('.in_hospital_email_reception_flg input[type=radio]:checked').val() == '0') {
                      $('.confirmation_email_reception_flag input:checkbox').prop('disabled', true);
                  } else {
                      $('.confirmation_email_reception_flag input:checkbox').prop('disabled', false);
                  }
              };
              $('.in_hospital_email_reception_flg input:radio').change(function() {
                  change();
              })
              change();
          })();

        /* ---------------------------------------------------
           // 受付メール受信アドレス設定
          -----------------------------------------------------*/
          (function () {
              const changeEmailReceptionFlg = function() {
                  if ($('.email_reception_flg input[type=radio]:checked').val() == '0') {
                      $('.reception_type_flag input:checkbox').prop('disabled', true);
                  } else {
                      $('.reception_type_flag input:checkbox').prop('disabled', false);
                  }
              };
              $('.email_reception_flg input:radio').change(function() {
                changeEmailReceptionFlg();
              })
              changeEmailReceptionFlg();
          })();
      })(jQuery);
  </script>
@endpush

<style>
.gray-color {
    color: #a5b6bf !important;
}
</style>
