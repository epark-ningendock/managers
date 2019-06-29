<div class="box-body">

  {{-- PV・予約 --}}
  <label for="name">PV・予約</label>
  <div class="row">
    <div class='col-sm-4'>
      <p>PV件数</p>
    </div>
    <div class="form-group col-sm-4 @if ($errors->has('name')) has-error @endif">
      <input type="text" class="form-control" id="name" name="name" value="" placeholder="名前を入力">
    </div>
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    <div class='col-sm-4 checkbox'>
      <label class="ml-3">
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          ピックアップ
      </label>
    </div>
  </div>

  {{-- アクセスのついて --}}
  <label for="email">アクセスについて</label>
  <div class='checkbox'>
    <label class="ml-3">
      {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
      駐車場あり
    </label>
  </div>
  <div class="form-group @if ($errors->has('email')) has-error @endif">
      <textarea class="form-control" id="course_point" name="course_point" rows="5">
        
      </textarea>
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
  </div>
  <div class='row'>
    <div class='checkbox col-sm-6'>
      <label class="ml-3">
        {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
        送迎サービスあり
      </label>
    </div>
    <div class='checkbox col-sm-6'>
      <label class="ml-3">
        {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
        駐車場あり
      </label>
      <input type="email" class="form-control" id="email" name="email" value="" placeholder="メールアドレスを入力して">
    </div>
  </div>

  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
      <label for="login_id">クレジットカード対応</label>
      <textarea class="form-control" id="course_point" name="course_point" rows="5">
          
      </textarea>
      @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  {{-- 外国語対応 --}}
  <label for="login_id">外国語対応</label>
  <div class='checkbox ml-3'>
      <label>
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          英語
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          中国語
      </label>

      <label class="ml-3">
          {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
          {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
          韓国語
      </label>
  </div>
  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
      <textarea class="form-control" id="course_point" name="course_point" rows="5" placeholder="HTMLで記述することも可能です">
          
      </textarea>
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
  </div>

  {{-- 認定施設について --}}
  <div class="form-group @if ($errors->has('login_id')) has-error @endif">
    <label for="login_id">認定施設について</label>
    <div class='checkbox ml-3'>
        <label>
            {{ Form::hidden('in_hospital_confirmation_email_reception_flg') }}
            {{ Form::checkbox('in_hospital_cancellation_email_reception_flg') }}
            日本認定ドック学会 機能評価認定施設
        </label>
    </div>
    @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
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