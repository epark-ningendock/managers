<div class="modal fade" id="email-history-modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('messages.email') }}</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">

          <tr>
            <td class="gray-cell-bg">
              <label>{{ __('送信先メールアドレス') }}</label>
            </td>
            <td id="customer-email">
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
              <label>{{ __('差出人メールアドレス') }}</label>
            </td>
            <td>
              <div class="form-group">
                {{ env('EPARK_EMAIL_ADDRESS') }}
              </div>
            </td>
          </tr>
          <tr>
            <td class="gray-cell-bg">
              <label>{{ __('テンプレート') }}</label>
            </td>
            <td>
              <div class="form-group">
                <select class="form-control" disabled="">
                  <option value=""></option>
                </select>

              </div>
            </td>
            <td>
              <button  type="button" class="btn btn-primary btn-lg" disabled>反映する</button>
            </td>
          </tr>

          <tr>
            <td class="gray-cell-bg">
              <label>{{ __('件名') }}</label>
            </td>
            <td>
              <div class="form-group">
                <input type="text" class="form-control" id="p-title" disabled />
              </div>
            </td>
          </tr>

          <tr>
            <td class="gray-cell-bg">
              <label>{{ __('本文') }}</label>
            </td>
            <td>
              <div class="form-group">
                <textarea class="form-control" id="p-contents" cols="30" rows="5" disabled></textarea>
              </div>
            </td>
          </tr>
        </table>

        <div class="text-center mt-0">
          <button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">{{ trans('close') }}</button>
        </div>
      </div>
    </div>
  </div>
</div>
