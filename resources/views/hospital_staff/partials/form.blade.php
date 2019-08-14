@include('layouts.partials.message')
<div class="form-entry">
    <div class="box-body">
        <input type="hidden" name="updated_at" value="{{ isset($hospital_staff) ? $hospital_staff->updated_at : null }}">
        <div class="form-group">
            <label for="name">医療機関スタッフ名</label>
            <div class="form-group @if( $errors->has('name'))  has-error @endif">
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ ( isset($hospital_staff->name) ) ? $hospital_staff->name : Input::old('name') }}"
                       placeholder="医療機関スタッフ名">
                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            </div>
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <div class="form-group @if( $errors->has('email'))  has-error @endif">
                <input type="email" class="form-control" id="email" name="email"
                       value="{{ ( isset($hospital_staff->email) ) ? $hospital_staff->email : Input::old('email') }}"
                       placeholder="メールアドレス">
                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
            </div>
        </div>

        <div class="form-group">
            <label for="login_id">ログインID</label>
            <div class="form-group @if( $errors->has('login_id'))  has-error @endif">
                <input type="text" class="form-control" id="login_id" name="login_id"
                       value="{{ ( isset($hospital_staff->login_id) ) ? $hospital_staff->login_id : Input::old('login_id') }}"
                       placeholder="ログインID">
                @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
            </div>
        </div>

        @if (!isset($hospital_staff))
            <label for="password">パスワード</label>
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

        <div class="box-footer">
            <a href="{{ route('hospital-staff.index') }}" class="btn btn-default">戻る</a>
            <button type="submit" class="btn btn-primary">保存</button>
        </div>

    </div>
</div>