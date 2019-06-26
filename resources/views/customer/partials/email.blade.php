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
                        <input type="hidden" name="destination_mail_address" value="{{ $customer->email }}">
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
                        <select class="form-control" name="hospital_email" id="hospital_email">
                            <option value="">Select Email</option>
                            @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->email }}">{{ $hospital->email }}</option>
                            @endforeach
                        </select>
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
                    <label for="subject">{{ __('件名') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" class="form-control" name="subject" id="subject" />
                    </div>
                </td>
            </tr>

            <tr>
                <td class="gray-cell-bg">
                    <label for="message">{{ __('本文') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <textarea class="form-control" name="message" id="message" cols="30" rows="5"></textarea>
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
            「反映する」を
        -----------------------------------------------------*/
        $('#reflect-template').click(function () {
            console.log("Hi Ryu.");
            var val = $('[name=email_template]').val();
            console.log(val);
            var txt = $('[name=email_template] option:selected').text();
            console.log(txt);
        });

    })(jQuery);


</script>
