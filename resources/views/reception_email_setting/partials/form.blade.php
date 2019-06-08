@php
  use \App\Enums\ReceptionEmailSetting;
@endphp

<br>
<p class="text-bold">【メール設定】</p>
<p class='ml-3'>差出人メールアドレス：epark@example.com</p>
<br>

<div class="form-group @if ($errors->has('web_reception')) has-error @endif">

  <p class="text-bold">【受信希望者・院内受付メール送信設定】</p>
  <div class="radio ml-3">
    <label>
      <input type="radio" name="in_hospital_email_reception_flg"
            {{ old('in_hospital_email_reception_flg', (isset($reception_email_setting) ? $reception_email_setting->in_hospital_email_reception_flg : null) ) == ReceptionEmailSetting::Accept ? 'checked' : '' }}
            value="{{ ReceptionEmailSetting::Accept }}">
      メールを配信する
    </label>
    <label class="ml-3">
      <input type="radio" name="in_hospital_email_reception_flg"
            {{ old('in_hospital_email_reception_flg', (isset($reception_email_setting) ? $reception_email_setting->in_hospital_email_reception_flg : null) ) == ReceptionEmailSetting::NotAccept ? 'checked' : '' }}
            value="{{ ReceptionEmailSetting::NotAccept }}">
      メールを配信しない
    </label>
  </div>
  <br>

  <div class='checkbox ml-3'>
      <label for="in_hospital_confirmation_email_reception_flg">
          {{ Form::checkbox('in_hospital_confirmation_email_reception_flg', 1, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_confirmation_email_reception_flg : null) == ReceptionEmailSetting::Accept ? true : false) }}
          受付確定時
      </label>

      <label class="ml-3" for="in_hospital_change_email_reception_flg">
          {{ Form::checkbox('in_hospital_change_email_reception_flg', 1, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_change_email_reception_flg : null) == ReceptionEmailSetting::Accept ? true : false) }}
          受付変更時
      </label>

      <label class="ml-3" for="in_hospital_cancellation_email_reception_flg">
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg', 1, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_cancellation_email_reception_flg : null) == ReceptionEmailSetting::Accept ? true : false) }}
          キャンセル時
      </label>
  </div>
  <br>

  <p class="text-bold">【受付メール受信アドレス設定】</p>
  <div class="radio ml-3">
    <label>
      <input type="radio" name="email_reception_flg"
            {{ old('email_reception_flg', (isset($reception_email_setting) ? $reception_email_setting->email_reception_flg : null) ) == ReceptionEmailSetting::Accept ? 'checked' : '' }}
            value="{{ ReceptionEmailSetting::Accept }}">
      受け取る
    </label>
    <label class="ml-3">
      <input type="radio" name="email_reception_flg"
            {{ old('email_reception_flg', (isset($reception_email_setting) ? $reception_email_setting->email_reception_flg : null) ) == ReceptionEmailSetting::NotAccept ? 'checked' : '' }}
            value="{{ ReceptionEmailSetting::NotAccept }}">
      受け取らない
    </label>
  </div>
  <br>

  <div class='checkbox ml-3'>
      <label for="in_hospital_reception_email_flg">
          {{ Form::checkbox('in_hospital_reception_email_flg', 1, (isset($reception_email_setting) ? $reception_email_setting->in_hospital_reception_email_flg : null) == ReceptionEmailSetting::Accept ? true : false) }}
          院内受付
      </label>
  
      <label class="ml-3" for="web_reception_email_flg">
          {{ Form::checkbox('web_reception_email_flg', 1, (isset($reception_email_setting) ? $reception_email_setting->web_reception_email_flg : null) == ReceptionEmailSetting::Accept ? true : false) }}
          WEB受付
      </label>
  </div>
  <br>

  <p class='ml-3'>受信メールアドレス1</p>
  {{ Form::text('reception_email1', (isset($reception_email_setting->reception_email1) ) ? $reception_email_setting->reception_email1 : Input::old('reception_email1'), ['class' => 'form-control ml-3', 'id' => 'reception_email1', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス2</p>
  {{ Form::text('reception_email2', (isset($reception_email_setting->reception_email2) ) ? $reception_email_setting->reception_email2 : Input::old('reception_email2'), ['class' => 'form-control ml-3', 'id' => 'reception_email2', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス3</p>
  {{ Form::text('reception_email3', (isset($reception_email_setting->reception_email3) ) ? $reception_email_setting->reception_email3 : Input::old('reception_email3'), ['class' => 'form-control ml-3', 'id' => 'reception_email3', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス4</p>
  {{ Form::text('reception_email4', (isset($reception_email_setting->reception_email4) ) ? $reception_email_setting->reception_email4 : Input::old('reception_email4'), ['class' => 'form-control ml-3', 'id' => 'reception_email4', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class='ml-3'>受信メールアドレス5</p>
  {{ Form::text('reception_email5', (isset($reception_email_setting->reception_email5) ) ? $reception_email_setting->reception_email5 : Input::old('reception_email5'), ['class' => 'form-control ml-3', 'id' => 'reception_email5', 'placeholder' => 'メールアドレスを入力してください']) }}
  <br>

  <p class="text-bold">【EPARK人間ドック受付設定】</p>
  <div class='checkbox ml-3'>
      <label for="epark_in_hospital_reception_mail_flg">
          {{ Form::checkbox('epark_in_hospital_reception_mail_flg', 1, (isset($reception_email_setting) ? $reception_email_setting->epark_in_hospital_reception_mail_flg : null) == ReceptionEmailSetting::Accept ? true : false) }}
          院内受付
      </label>

      <label class="ml-3" for="epark_web_reception_email_flg">
          {{ Form::checkbox('epark_web_reception_email_flg', 1, (isset($reception_email_setting) ? $reception_email_setting->epark_web_reception_email_flg : null) == ReceptionEmailSetting::Accept ? true : false) }}
          WEB受付
      </label>
  </div>
  <br>

  <div class="box-footer">
      <button type="submit" class="btn btn-primary">更新</button>
  </div>

  @if ($errors->has('web_reception')) <p class="help-block">{{ $errors->first('web_') }}</p> @endif
</div>