@include('commons.errorbag')
<form method="POST"  action="{{ route('hospital-staff.update', $hospital_staff->id) }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="box-body">

        <div class="form-group @if ($errors->has('name')) has-error @endif">
            <label for="name">名前を入力</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ ( $hospital_staff->name ) ? $hospital_staff->name : Input::old('name') }}" placeholder="名前を入力">
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('email')) has-error @endif">
            <label for="email">メールアドレスを入力して</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ ( $hospital_staff->email ) ? $hospital_staff->email : Input::old('email') }}" placeholder="メールアドレスを入力して">
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('login_id')) has-error @endif">
            <label for="login_id">ログインID</label>
            <input type="text" class="form-control" id="login_id" name="login_id" value="{{ ( $hospital_staff->login_id ) ? $hospital_staff->login_id : Input::old('login_id') }}" placeholder="ログインID">
            @if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('password')) has-error @endif">
            <label for="password">パスワード</label>
            <input type="password" class="form-control" id="password" name="password" value="{{  Input::old('password') }}" placeholder="パスワード">
            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
        </div>

        <div class="box-footer">
            <a href="{{ route('hospital-staff.index') }}" class="btn btn-default">バック</a>
            <button type="submit" class="btn btn-primary">つくる</button>
        </div>

    </div>



</form>