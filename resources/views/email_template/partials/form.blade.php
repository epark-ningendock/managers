<div class="box-body">
  <p class="text-bold">差出人名：unei@eparkdock.com</p>
  <p class="text-bold">差出人メールアドレス：unei@eparkdock.com</p>
  <input type="hidden" name="lock_version" value="{{ $email_template->lock_version or '' }}"  />
  <div class="form-group @if ($errors->has('title')) has-error @endif">
    <label for="title">テンプレート名（件名）</label>
    {{ Form::text('title', (isset($email_template->title) ) ? $email_template->title : Input::old('title'), ['class' => 'form-control', 'id' => 'title', 'placeholder' => '件名を入力してください']) }}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
  </div>
  
  <div class="form-group">
    <label for="text">本文</label>
    {{ Form::textarea('text', (isset($email_template) ) ? $email_template->text : null, ['class' => 'form-control', 'id' => 'text', 'rows' => '10', 'placeholder' => '本文を入力してください']) }}
    <span class="pull-right">0/20000文字</span>
  </div>

  <div class="box-footer">
      <a href="{{ route('email-template.index') }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">作成</button>
  </div>
</div>
@section('script')
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // character count
          -----------------------------------------------------*/
          (function () {
              $('textarea').on('keyup', function() {
                  const len = $(this).val().length;
                  if (len > 20000) {
                      $(this).val($(this).val().substring(0, 19999));
                  } else {
                      $(this).next('span').text(len + '/20000文字');
                  }
              });
          })();
      })(jQuery);
  </script>
@stop