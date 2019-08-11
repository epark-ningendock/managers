<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">{{ trans('messages.email') }}</h4>
</div>
<div class="modal-body">
    <form method="post" action="{{ route('customer.email.send', ['customer_id' => $customer->id]) }}">
        {{ csrf_field() }}

        <table class="table table-bordered">
            <tr>
                <td class="gray-cell-bg">
                    <label for="customer_email">{{ __('送信先メールアドレス') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        {{ $customer->email }}
                        <input type="hidden" name="customer_email" value="{{ $customer->email }}">
                    </div>
                </td>
            </tr>

            {{-- <tr>
                <td class="gray-cell-bg">
                    <label for="appointed_submissions">{{ __('任命された応募') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" class="form-control" name="appointed_submissions" id="appointed_submissions" />
                    </div>
                </td>
            </tr> --}}
            
            <tr>
                <td class="gray-cell-bg">
                    <label for="hospital_email">{{ __('差出人メールアドレス') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        {{ $hospital->email }}
                        <input type="hidden" name="hospital_email" value="{{ $hospital->email }}">
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
                        <textarea class="form-control" name="text" id="text" cols="30" rows="5"></textarea>
                    </div>
                </td>
            </tr>




        </table>



        <div class="text-center mb-5 pb-5">
            <button type="submit" class="btn btn-primary btn-lg">{{trans('messages.send_email') }}</button>
        </div>

        <div class="text-center mt-0">
            <button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">{{ trans('close') }}</button>
        </div>

    </form>
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
                $('#text').val('');
                return
            } 
            var email_templates = JSON.parse("{{ json_encode($email_templates) }}".replace(/&quot;/g,'"'));

            target = email_templates.filter(function(email_template) {
                return email_template.id == targetId
            }).shift();
            
            $('#title').val(target.title);
            $('#text').val(target.text);
        });
        
        $("textarea").change( function() {
            var txtVal = $(this).val();
            txtVal = txtVal.replace(/\r\n/g, "&lt;br /&gt;<br />");
            txtVal = txtVal.replace(/(\n|\r)/g, "&lt;br /&gt;<br />");
            $('#testpre').html('<p>'+ txtVal +'</p>');
        });
    })(jQuery);


</script>
