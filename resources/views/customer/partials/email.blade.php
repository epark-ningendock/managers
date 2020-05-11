<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">{{ trans('messages.email') }}</h4>
</div>
<div class="modal-body">
    <form method="post" action="{{ route('customer.email.send', ['customer_id' => $customer->id]) }}" id="email-form">
        {{ csrf_field() }}

        <table class="table table-bordered">
            <tr>
                <td class="gray-cell-bg">
                    <label for="customer_email">{{ __('送信先メールアドレス') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        {{ $customer->email }}
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    </div>
                </td>
            </tr>

            <tr>
                <td class="gray-cell-bg">
                    <label>{{ __('差出人名') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        {{ env('EPARK_EMAIL_ADDRESS') }}
                    </div>
                </td>
            </tr>
            
            <tr>
                <td class="gray-cell-bg">
                    <label for="hospital_email">{{ __('差出人メールアドレス') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        {{ env('EPARK_EMAIL_ADDRESS') }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="gray-cell-bg">
                    <label for="template">{{ __('テンプレート') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <select class="form-control" name="email_template" id="email_template">
                            <option value=""></option>
                            @foreach($email_templates as $email_template)
                                <option value="{{ $email_template['id'] }}">{{ $email_template['title'] }}</option>
                            @endforeach
                        </select>
                    
                    </div>
                </td>
                <td>
                    <button id="reflect-template" type="button" class="btn btn-primary btn-lg">反映する</button>
                </td>
            </tr>

            <tr>
                <td class="gray-cell-bg">
                    <label for="title">{{ __('件名') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" id="title" />
                    </div>
                </td>
            </tr>

            <tr>
                <td class="gray-cell-bg">
                    <label for="text">{{ __('本文') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <textarea class="form-control" name="contents" id="contents" cols="30" rows="5"></textarea>
                    </div>
                </td>
            </tr>
        </table>

        <div class="text-center mb-5">
            <button type="submit" class="btn btn-primary btn-lg" id="btn-send">{{trans('messages.send_mail') }}</button>
        </div>

    </form>

    <div class="mail-history">
        @include('customer.partials.email-history')
    </div>


    <div class="text-center mt-0">
        <button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">{{ trans('close') }}</button>
    </div>

</div>

<script type="text/javascript">

    (function ($) {
        /* ---------------------------------------------------
            「反映する」を押した時に、画面にテンプレートを反映する
        -----------------------------------------------------*/
        $('#reflect-template').click(function () {
            var targetId = $('[name=email_template]').val();
            if (!targetId) {
                $('#title').val('');
                $('#contents').val('');
                return
            } 
            var email_templates = JSON.parse("{{ json_encode($email_templates) }}"
                .replace(/&quot;/g,'"')
                .replace(/(\r\n)/g, '\n')
                .replace(/(\r)/g,   '\n')
                .replace(/(\n)/g,  '\\n')
            );

            target = email_templates.filter(function(email_template) {
                return email_template.id == targetId
            }).shift();
            
            $('#title').val(target.title);
            $('#contents').val(target.text);
        });
        
        $("textarea").change( function() {
            var txtVal = $(this).val();
            txtVal = txtVal.replace(/\r\n/g, "&lt;br /&gt;<br />");
            txtVal = txtVal.replace(/(\n|\r)/g, "&lt;br /&gt;<br />");
            $('#testpre').html('<p>'+ txtVal +'</p>');
        });

        /* ---------------------------------------------------
          // ajax submit
          -----------------------------------------------------*/
        (function() {
            $('#email-form').submit(function(event) {
                event.preventDefault();
                event.stopPropagation();
                $('#btn-send').html("{{ trans('messages.sending_email') }}").prop('disabled', true);
                $.ajax({
                    url: $('#email-form').attr('action'),
                    method: 'post',
                    data: $('#email-form').serialize(),
                    success: function(data){
                        if(data.success) {
                            //hide modal box
                            $('.std-modal-box').modal('hide');
                            $('.ajax-data-popup').modal('hide');

                            // showing success message
                            const message = $('<div class="alert alert-success alert-block">
                                                 <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                                 <strong class="white-space">${data.success}</strong>
                                               </div>').prependTo('section.content>div.row>div.col-xs-12>div.box');

                            //scroll up for message box
                            $('html, body').animate({
                                scrollTop: 0
                            }, 300);

                            $('.alert-success').fadeOut(2000, function() {
                                $('.alert-success').remove();
                            });

                        } else {
                            // error message processing for each control
                            $.each(['title', 'contents'], function(index, key){
                                const div = $(`#${key}`).parent();
                                div.removeClass('has-error');
                                div.find('p').remove();

                                if(data.errors[key]) {
                                    div.addClass('has-error');
                                    div.append($(`<p class='help-block text-left'>${data.errors[key][0]}</p>`));
                                }

                            });
                        }
                    },
                    complete: function() {
                        $('#btn-send').html("{{trans('messages.send_mail') }}").prop('disabled', false);
                    }
                });
            });
        })();

    })(jQuery);


</script>
