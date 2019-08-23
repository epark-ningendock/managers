<div class="form-entry">
    <div class="box-body">
        <h2>メールテンプレート</h2>
      <p class="text-bold">差出人名：unei@eparkdock.com</p>
      <p class="text-bold">差出人メールアドレス：unei@eparkdock.com</p>
      <div class="form-group py-sm-1 @if ($errors->has('title')) has-error @endif">
        <label for="title">テンプレート名（件名）<span class="form_required">必須</span></label>
        {{ Form::text('title', old('title', (isset($email_template->title) ) ? $email_template->title : null), ['class' => 'form-control', 'id' => 'title', 'placeholder' => '件名を入力してください']) }}
        @if ($errors->has('title')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('title') }}</p> @endif
      </div>
        <div class="form-group py-sm-1 @if ($errors->has('text')) has-error @endif">
            <label for="text">本文</label>
            {{ Form::textarea('text', old('text',(isset($email_template) ) ? $email_template->text : null), ['class' => 'form-control', 'id' => 'text', 'rows' => '10', 'placeholder' => '本文を入力してください']) }}
            {{-- <span class="pull-right">{{ strlen(old('text', (isset($email_template) ) ? $email_template->text : null)) }}/20000文字</span> --}}
            <span class="pull-right">0/20000文字</span>
            @if ($errors->has('text')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('text') }}</p> @endif
        </div>

      <div class="box-footer">
          <a href="{{ route('email-template.index') }}" class="btn btn-default">戻る</a>
          <button type="submit" class="btn btn-primary">作成</button>
      </div>
    </div>
</div>
@section('script')
  <script>
      (function ($) {
        $(document).ready(function($){
            const len = $('textarea').val().length;
            if (len > 20000) {
                $('textarea').val($('textarea').val().substring(0, 20000));
            } else {
                $('textarea').next('span').text(len + '/20000文字');
            }
        });
        /* ---------------------------------------------------
        // character count
        -----------------------------------------------------*/
        (function () {
            $('textarea').on('keyup', function() {
                const len = $(this).val().length;
                if (len > 20000) {
                    $(this).val($(this).val().substring(0, 20000));
                } else {
                    $(this).next('span').text(len + '/20000文字');
                }
            });
        })();
      })(jQuery);
  </script>
@stop
