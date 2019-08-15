<div class="form-entry">
    <div class="box-body">
        <h2>メールテンプレート</h2>
      <p class="text-bold">差出人名：Epark</p>
      <p class="text-bold">差出人メールアドレス：Unei@eparkdock.com</p>
      <div class="form-group py-sm-1 @if ($errors->has('title')) has-error @endif">
        <label for="title">テンプレート名（件名）<span class="form_required">必須</span></label>
        {{ Form::text('title', (isset($email_template->title) ) ? $email_template->title : Input::old('title'), ['class' => 'form-control', 'id' => 'title', 'placeholder' => '件名を入力してください']) }}
        @if ($errors->has('title')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('title') }}</p> @endif
      </div>
        <div class="form-group py-sm-1 @if ($errors->has('text')) has-error @endif">
            <label for="text">本文</label>
            {{ Form::textarea('text', (isset($email_template) ) ? $email_template->text : null, ['class' => 'form-control', 'id' => 'text', 'rows' => '10', 'placeholder' => '本文を入力してください']) }}
            <span class="pull-right">0/20000文字</span>
            @if ($errors->has('text')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('text') }}</p> @endif
        </div>


        <div class="bill-mail-setup bms">

            <div class="form-group @if ($errors->has('billing_email_flg')) has-error @endif">
                <div class="row mb-3 mt-3">
                    <div class="col-md-4">
                        <label for="billing_email_flg">{{ trans('messages.billing_email_flg') }} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" class="billing_email_flg" name="billing_email_flg" value="1" @if( old('billing_email_flg') === '1' ) checked @endif>{{ trans('messages.billing_email_flg_receive') }}
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="billing_email_flg" name="billing_email_flg" value="0" @if( old('billing_email_flg') === '0' ) checked @endif> {{ trans('messages.billing_email_flg_not_accept') }}
                        </label>

                        @if ($errors->has('billing_email_flg') && ($errors->first('billing_email_flg') == '請求メールの設定は、必ず指定してください。')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email_flg') }}</p> @endif

                    </div>
                </div>
            </div>

            <div class="bill-fields-box">

                <div class="form-group @if ($errors->has('billing_email1')) has-error @endif">
                    <div class="row">
                    <div class="col-md-4">
                        <label for="billing_email1">{{ trans('messages.billing_email1') }}</label>
                    </div>
                    <div class="col-md-8">
                            <input type="text" class="form-control" id="billing_email1" name="billing_email1" value="{{ old('billing_email1') }}" />
                            @if ($errors->has('billing_email1')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email1') }}</p> @endif
                    </div>
                </div>
                </div>

                <div class="form-group @if ($errors->has('billing_email2')) has-error @endif">
                    <div class="row">
                    <div class="col-md-4">
                        <label for="billing_email2">{{ trans('messages.billing_email2') }}</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="billing_email2" name="billing_email2" value="{{ old('billing_email2') }}" />
                        @if ($errors->has('billing_email2')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email2') }}</p> @endif
                    </div>
                </div>
                </div>


                <div class="form-group @if ($errors->has('billing_email3')) has-error @endif">
                    <div class="row">
                    <div class="col-md-4">
                        <label for="billing_email3">{{ trans('messages.billing_email3') }}</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="billing_email3" name="billing_email3" value="{{ old('billing_email3') }}" />
                        @if ($errors->has('billing_email3')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email3') }}</p> @endif
                    </div>
                </div>
                </div>

                <div class="form-group @if ($errors->has('billing_fax_number')) has-error @endif">
                    <div class="row">
                    <div class="col-md-4">
                        <label for="billing_fax_number">{{ trans('messages.billing_fax_number') }}</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="billing_fax_number" name="billing_fax_number" value="{{ old('billing_fax_number') }}" />
                        @if ($errors->has('billing_fax_number')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_fax_number') }}</p> @endif
                        <br/><br/>
                        <div class="has-error">
                            @if ($errors->has('billing_email_flg') && ($errors->first('billing_email_flg') !== '請求メールの設定は、必ず指定してください。')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('billing_email_flg') }}</p> @endif
                        </div>
                    </div>
                </div>
                </div>

            </div>

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

          /* ---------------------------------------------------
           // Billing mail toggle
          -----------------------------------------------------*/
          $(document).on('change', '.billing_email_flg', function(){
              const inputs = $('.bill-fields-box input');
              if ( $(this).val() == 1) {
                  inputs.each(function(){
                      $(this).attr('disabled', false);
                  });
              } else {
                  inputs.each(function(){
                      $(this).addClass('YYYY');
                      $(this).attr('disabled', true);
                  });
              }
          });

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
