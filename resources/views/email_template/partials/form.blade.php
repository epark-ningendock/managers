<div class="box-body">
  <p class="text-bold">差出人名：Epark</p>
  <p class="text-bold">差出人メールアドレス：epark@example.com</p>
  
  <div class="form-group @if ($errors->has('title')) has-error @endif">
    <label for="title">件名</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ ( isset($email_template->title) ) ? $email_template->title : Input::old('title') }}" placeholder="件名を入力してください">
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
  </div>
  
  <div class="form-group">
    <label for="text">本文</label>
    <textarea class="form-control" id="text" name="text" rows="10">
      {{ old('text', (isset($course) ? $email_template->text : null)) }}
    </textarea>
    <span class="pull-right">0/20000文字</span>
  </div>

  <div class="box-footer">
      <a href="{{ route('email-template.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">作成</button>
  </div>
</div>