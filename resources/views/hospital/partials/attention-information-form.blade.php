<div class="box-body">

  {{-- PV・予約 --}}
  <label for="name">PV・予約</label>
  <div class="row">
    <div class='col-sm-4'>
        {{-- pv_countを使う --}}
      <p>PV件数</p>
    </div>
    <div class="form-group col-sm-4 @if ($errors->has('pvad')) has-error @endif">
      <input type="text" class="form-control" id="pvad" name="pvad" value="" placeholder="名前を入力">
    </div>
    @if ($errors->has('pvad')) <p class="help-block">{{ $errors->first('pvad') }}</p> @endif
    <div class='col-sm-4 checkbox'>
      <label class="ml-3">
          {{ Form::hidden('is_pickup') }}
          {{ Form::checkbox('is_pickup') }}
          ピックアップ
      </label>
    </div>
  </div>

  {{-- アクセスのついて --}}
  <label>アクセスについて</label>
  <div class='checkbox'>
    <label class="ml-3">
      {{ Form::hidden('is_parking') }}
      {{ Form::checkbox('is_parking') }}
      駐車場あり
    </label>
  </div>
  <div class="form-group @if ($errors->has('parking_supplement')) has-error @endif">
      <textarea class="form-control" id="parking_supplement" name="parking_supplement" rows="5"> 
      </textarea>
    @if ($errors->has('parking_supplement')) <p class="help-block">{{ $errors->first('parking_supplement') }}</p> @endif
  </div>
  <div class='row'>
    <div class='checkbox col-sm-6'>
      <label class="ml-3">
        {{ Form::hidden('is_transfer') }}
        {{ Form::checkbox('is_transfer') }}
        送迎サービスあり
      </label>
    </div>
    <div class='checkbox col-sm-6'>
      <label class="ml-3">
        {{ Form::hidden('station1') }}
        {{ Form::checkbox('station1') }}
        駅近
      </label>
      <input type="email" class="form-control" id="email" name="email" value="" placeholder="メールアドレスを入力して">
    </div>
  </div>

  <div class="form-group @if ($errors->has('credit_card_supplement')) has-error @endif">
      <label for="credit_card_supplement">クレジットカード対応</label>
      <textarea class="form-control" id="credit_card_supplement" name="credit_card_supplement" rows="5">
          
      </textarea>
      @if ($errors->has('credit_card_supplement')) <p class="help-block">{{ $errors->first('credit_card_supplement') }}</p> @endif
  </div>

  {{-- 外国語対応 --}}
  <label>外国語対応</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('foreign_language_correspondence') }}
          {{ Form::checkbox('foreign_language_correspondence') }}
          英語
      </label>

      <label class="ml-3">
          {{ Form::hidden('foreign_language_correspondence') }}
          {{ Form::checkbox('foreign_language_correspondence') }}
          中国語
      </label>

      <label class="ml-3">
          {{ Form::hidden('foreign_language_correspondence') }}
          {{ Form::checkbox('foreign_language_correspondence') }}
          韓国語
      </label>
  </div>
  <div class="form-group @if ($errors->has('foreign_language_supplement')) has-error @endif">
      <textarea class="form-control" id="foreign_language_supplement" name="foreign_language_supplement" rows="5" placeholder="HTMLで記述することも可能です">
          
      </textarea>
    @if ($errors->has('foreign_language_supplement')) <p class="help-block">{{ $errors->first('foreign_language_supplement') }}</p> @endif
  </div>

  {{-- 認定施設について --}}
  <div class="form-group @if ($errors->has('is_certification')) has-error @endif">
    <label for="is_certification">認定施設について</label>
    <div class='checkbox ml-3'>
        <label>
            {{ Form::hidden('is_certification') }}
            {{ Form::checkbox('is_certification') }}
            日本認定ドック学会 機能評価認定施設
        </label>
    </div>
    @if ($errors->has('is_certification')) <p class="help-block">{{ $errors->first('is_certification') }}</p> @endif
  </div>

  {{-- 女性対応 --}}
  <label for="login_id">女性対応</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          レディースデーあり
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          女性専用エリアあり
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          パウダールームあり
      </label>
  </div>

  {{-- お子様対応 --}}
  <label for="login_id">お子様対応</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          キッズスペースあり
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          託児所
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          子連れ対応可能
      </label>
  </div>
  <textarea class="form-control" id="course_point" name="course_point" rows="5" placeholder="HTMLで記述することも可能です">
          
  </textarea>

  {{-- 施設について --}}
  <label for="login_id">施設について</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          検診専用施設
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          検診専用エリアあり
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          バリアフリー対応
      </label>
  </div>

  {{-- 食事について --}}
  <label for="login_id">食事について</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          院内食堂・レストランあり（予約なしで利用可能）
      </label>
  </div>

  {{-- 併用施設について --}}
  <label for="login_id">併用施設について</label>
  <textarea class="form-control" id="course_point" name="course_point" rows="5" placeholder="HTMLで記述することも可能です">
        
  </textarea>

  {{-- 周辺施設について --}}
  <label for="login_id">周辺施設について</label>
  <textarea class="form-control" id="course_point" name="course_point" rows="5" placeholder="HTMLで記述することも可能です">
        
  </textarea>

  {{-- プライバシー配慮について --}}
  <label for="login_id">プライバシー配慮</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          更衣室専有あり（一人着替えスペース）
      </label>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          個室採血室あり
      </label>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          個室採血室あり
      </label>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          呼び出し配慮あり
      </label>
  </div>

  {{-- 検査結果 --}}
  <label for="login_id">検索結果</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          検査結果即日発行対応
      </label>
  </div>

  {{-- フリーエリア --}}
  <label for="login_id">フリーエリア</label>
  <textarea class="form-control" id="course_point" name="course_point" rows="5" placeholder="HTMLで記述することも可能です">
      
  </textarea>

  {{-- 検索ワード --}}
  <label for="login_id">検索ワード</label>
  <textarea class="form-control" id="course_point" name="course_point" rows="5" placeholder="HTMLで記述することも可能です">
    
  </textarea>

  <div class="box-footer">
      <a href="{{ route('hospital.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">保存する</button>
  </div>

</div>

<style>
.red {
  color: red
}
/* webkit */
::-webkit-input-placeholder {
    color:    #999;
}

/* firefox */
:-moz-placeholder {
    color:    #999;
}
</style>