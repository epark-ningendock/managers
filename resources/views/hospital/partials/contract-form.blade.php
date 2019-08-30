<div class="form-group @if( $errors->has('contractor_name_kana'))  has-error @endif">
    <label for="contractor_name_kana" class="col-md-4">契約者名（フリガナ）</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="contractor_name_kana" name="contractor_name_kana" value="{{ old('contractor_name_kana', (isset($contract_information->contractor_name_kana) ) ?: null) }}" />
        @if ($errors->has('contractor_name_kana')) <p class="help-block">{{ $errors->first('contractor_name_kana') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('contractor_name'))  has-error @endif">
    <label for="contractor_name" class="col-md-4">契約者名</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="contractor_name" name="contractor_name" value="{{ old('contractor_name', (isset($contract_information->contractor_name) ) ?: null) }}" />
        @if ($errors->has('contractor_name')) <p class="help-block">{{ $errors->first('contractor_name') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('application_date'))  has-error @endif">
    <label for="application_date" class="col-md-4">申込日</label>
    <div class="col-md-8 form-inline">
        {{ Form::text('application_date', old('application_date'), ['class' => 'datetimepicker form-control', 'id' => 'application_date-field', 'placeholder' => '2019-04-01']) }}
        @if ($errors->has('application_date')) <p class="help-block">{{ $errors->first('application_date') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('billing_start_date'))  has-error @endif">
    <label for="billing_start_date" class="col-md-4">課金開始日</label>
    <div class="col-md-8 form-inline">
        {{ Form::text('billing_start_date', old('billing_start_date'), ['class' => 'datetimepicker form-control', 'id' => 'billing_start_date-field', 'placeholder' => '2019-04-01']) }}
        @if ($errors->has('billing_start_date')) <p class="help-block">{{ $errors->first('billing_start_date') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('cancellation_date'))  has-error @endif">
    <label for="cancellation_date" class="col-md-4">解約日</label>
    <div class="col-md-8 form-inline">
        {{ Form::text('cancellation_date', old('cancellation_date'), ['class' => 'datetimepicker form-control', 'id' => 'cancellation_date-field', 'placeholder' => '2019-04-01']) }}
        @if ($errors->has('cancellation_date')) <p class="help-block">{{ $errors->first('cancellation_date') }}</p> @endif
    </div>
</div>


<h5 class="sm-title">契約者情報</h5>


<div class="form-group @if( $errors->has('representative_name_kana'))  has-error @endif">
    <label for="representative_name_kana" class="col-md-4">代表者名（フリガナ）</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="representative_name_kana" name="representative_name_kana" value="{{ old('representative_name_kana', (isset($contract_information->representative_name_kana) ) ?: null) }}" />
        @if ($errors->has('representative_name_kana')) <p class="help-block">{{ $errors->first('representative_name_kana') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('representative_name'))  has-error @endif">
    <label for="representative_name" class="col-md-4">代表者名</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="representative_name" name="representative_name" value="{{ old('representative_name', (isset($contract_information->representative_name) ) ?: null) }}" />
        @if ($errors->has('representative_name')) <p class="help-block">{{ $errors->first('representative_name') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('postcode'))  has-error @endif">
    <label for="postcode" class="col-md-4">郵便番号</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="postcode" name="postcode" value="{{ old('postcode', (isset($contract_information->postcode) ) ?: null) }}" />
        @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('address'))  has-error @endif">
    <label for="address" class="col-md-4">住所</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="address" name="address"  value="{{ old('address', (isset($contract_information->address) ) ?: null) }}" />
        @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('tel'))  has-error @endif">
    <label for="tel" class="col-md-4">電話番号</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="tel" name="tel" value="{{ old('tel', (isset($contract_information->tel) ) ?: null) }}" />
        @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('fax'))  has-error @endif">
    <label for="fax" class="col-md-4">FAX番号</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="fax" name="fax" value="{{ old('fax', (isset($contract_information->fax) ) ?: null) }}" />
        @if ($errors->has('fax')) <p class="help-block">{{ $errors->first('fax') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('email'))  has-error @endif">
    <label for="email" class="col-md-4">メールアドレス</label>
    <div class="col-md-8">
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', (isset($contract_information->email) ) ?: null) }}" />
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>

<h5 class="sm-title">代表アカウント情報</h5>

<div class="form-group @if( $errors->has('login_id'))  has-error @endif">
    <label for="login_id" class="col-md-4">ログインID</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="login_id" name="login_id"  value="{{ old('login_id') }}" />
        @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('password'))  has-error @endif">
    <label for="password" class="col-md-4">パスワード</label>
    <div class="col-md-8">
        <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" />
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>
</div>

<h5 class="sm-title">ドックネットID</h5>
<div class="form-group @if( $errors->has('old_karada_dog_id'))  has-error @endif">
    <label for="old_karada_dog_id" class="col-md-4">からだドックID</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="old_karada_dog_id" name="old_karada_dog_id" value="{{ old('karada_dog_id', (isset($contract_information->karada_dog_id) ) ?: null) }}" />
        @if ($errors->has('old_karada_dog_id')) <p class="help-block">{{ $errors->first('old_karada_dog_id') }}</p> @endif
    </div>
</div>

<div class="form-group @if( $errors->has('karada_dog_id'))  has-error @endif">
    <label for="karada_dog_id" class="col-md-4">からだドック医療機関ID</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="karada_dog_id" name="karada_dog_id"  value="{{ old('karada_dog_id', (isset($contract_information->karada_dog_id) ) ?: null) }}" />
        @if ($errors->has('karada_dog_id')) <p class="help-block">{{ $errors->first('karada_dog_id') }}</p> @endif
    </div>
</div>
<div class="form-group @if( $errors->has('contract_info_search_word'))  has-error @endif">
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary pull-right">保存</button>
    </div>
</div>