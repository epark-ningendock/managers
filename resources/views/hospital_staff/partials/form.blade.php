<div class="box-body">

    <div class="form-group">
        <label for="name">名前を入力して下さい</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ ( isset($hospital_staff->name) ) ? $hospital_staff->name : Input::old('name') }}" placeholder="名前を入力して下さい">
    </div>

    <div class="form-group">
        <label for="email">メールアドレスを入力して下さい</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ ( isset($hospital_staff->email) ) ? $hospital_staff->email : Input::old('email') }}" placeholder="メールアドレスを入力して下さい">
    </div>

    <div class="form-group">
        <label for="login_id">ログインIDを入力して下さい</label>
        <input type="text" class="form-control" id="login_id" name="login_id" value="{{ ( isset($hospital_staff->login_id) ) ? $hospital_staff->login_id : Input::old('login_id') }}" placeholder="ログインIDを入力して下さい">
    </div>

    <div class="box-footer">
        <a href="{{ route('hospital-staff.index') }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">{{ $submit }}</button>
    </div>

</div>