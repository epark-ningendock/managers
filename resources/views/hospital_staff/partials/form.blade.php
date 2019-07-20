<div class="box-body">

    <div class="form-group">
        <label for="name">医療機関スタッフ名</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ ( isset($hospital_staff->name) ) ? $hospital_staff->name : Input::old('name') }}" placeholder="医療機関スタッフ名">
    </div>

    <div class="form-group">
        <label for="email">メールアドレス</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ ( isset($hospital_staff->email) ) ? $hospital_staff->email : Input::old('email') }}" placeholder="メールアドレス">
    </div>

    <div class="form-group">
        <label for="login_id">ログインID</label>
        <input type="text" class="form-control" id="login_id" name="login_id" value="{{ ( isset($hospital_staff->login_id) ) ? $hospital_staff->login_id : Input::old('login_id') }}" placeholder="ログインID">
    </div>

    <div class="box-footer">
        <a href="{{ route('hospital-staff.index') }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">保存</button>
    </div>

</div>