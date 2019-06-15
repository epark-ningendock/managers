<br>
<p class="text-bold">【メール設定】</p>
<p class='ml-3'>差出人メールアドレス：epark@example.com</p>
<br>

<div class="form-group @if ($errors->has('reception_email_setting')) has-error @endif">

  <p class="text-bold">【受信希望者・院内受付メール送信設定】</p>
  <div class="radio ml-3">
    <label>
      {{ Form::radio('in_hospital_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
      メールを配信する
    </label>
    <label class="ml-3">
      {{ Form::radio('in_hospital_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::NOT_ACCEPT ? true : false) }}
      メールを配信しない
    </label>
  </div>
  <br>

  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
          {{ Form::checkbox('in_hospital_confirmation_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_confirmation_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
          受付確定時
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_change_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
          {{ Form::checkbox('in_hospital_change_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_change_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
          受付変更時
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_cancellation_email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_cancellation_email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
          キャンセル時
      </label>
  </div>
  <br>

  <p class="text-bold">【受付メール受信アドレス設定】</p>
  <div class="radio ml-3">
    <label>
      {{ Form::radio('email_reception_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
      受け取る
    </label>
    <label class="ml-3">
      {{ Form::radio('email_reception_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->email_reception_flg : null) == \App\Enums\ReceptionEmailSetting::NOT_ACCEPT ? true : false) }}
      受け取らない
    </label>
  </div>
  <br>

  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
          {{ Form::checkbox('in_hospital_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
          院内受付
      </label>
  
      <label class="ml-3">
          {{ Form::hidden('web_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
          {{ Form::checkbox('web_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->web_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
          WEB受付
      </label>
  </div>
  <br>

  <p class='ml-3'>受信メールアドレス1</p>
  {{ Form::text('reception_email1' , (old('reception_email1')) ? old('reception_email1') : $reception_email_setting->reception_email1, ['class' => 'form-control ml-3', 'id' => 'reception_email1', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス2</p>
  {{ Form::text('reception_email2' , (old('reception_email2')) ? old('reception_email2') : $reception_email_setting->reception_email2, ['class' => 'form-control ml-3', 'id' => 'reception_email2', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス3</p>
  {{ Form::text('reception_email3' , (old('reception_email3')) ? old('reception_email3') : $reception_email_setting->reception_email3, ['class' => 'form-control ml-3', 'id' => 'reception_email3', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス4</p>
  {{ Form::text('reception_email4' , (old('reception_email4')) ? old('reception_email4') : $reception_email_setting->reception_email4, ['class' => 'form-control ml-3', 'id' => 'reception_email4', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス5</p>
  {{ Form::text('reception_email5' , (old('reception_email5')) ? old('reception_email5') : $reception_email_setting->reception_email5, ['class' => 'form-control ml-3', 'id' => 'reception_email5', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class="text-bold">【EPARK人間ドック受付設定】</p>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('epark_in_hospital_reception_mail_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
          {{ Form::checkbox('epark_in_hospital_reception_mail_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->epark_in_hospital_reception_mail_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
          院内受付
      </label>

      <label class="ml-3">
          {{ Form::hidden('epark_web_reception_email_flg', \App\Enums\ReceptionEmailSetting::NOT_ACCEPT) }}
          {{ Form::checkbox('epark_web_reception_email_flg', \App\Enums\ReceptionEmailSetting::ACCEPT, (isset($reception_email_setting) ? $reception_email_setting->epark_web_reception_email_flg : null) == \App\Enums\ReceptionEmailSetting::ACCEPT ? true : false) }}
          WEB受付
      </label>
  </div>
  <br>

  <div class="box-footer">
      <button type="submit" class="btn btn-primary">更新</button>
  </div>
</div>