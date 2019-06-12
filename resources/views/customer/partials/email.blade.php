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
                        <input type="text" class="form-control" name="customer_email" id="customer_email" />
                    </div>
                </td>
            </tr>


            <tr>
                <td class="gray-cell-bg">
                    <label for="hospital_email">{{ __('差出任命') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" class="form-control" name="hospital_email" id="hospital_email" />
                    </div>
                </td>
            </tr>

            <tr>
                <td class="gray-cell-bg">
                    <label for="sender_email_address">{{ __('差出人メールアドレス') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" class="form-control" name="sender_email_address" id="sender_email_address" />
                    </div>
                </td>
            </tr>


            <tr>
                <td class="gray-cell-bg">
                    <label for="template">{{ __('テンプレート') }}</label>
                </td>
                <td>
                    <div class="form-group">
                        <select name="template" id="template">
                            <option value="">test@mail.com</option>
                            <option value="">test@mail.com2</option>
                            <option value="">test@mail.com3</option>
                        </select>
                    </div>
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