<hr style="margin: 20px -15px;">
<div>
  <h4>送信履歴</h4>
  <div class="row">
    <div class="col-sm-6">
      @include('layouts.partials.pagination-label', ['paginator' => $mail_histories])
    </div>
    <div class="col-sm-6">
      <div class="form-inline pull-right">
        <label>表示件数</label>
        <select id="record_per_page" class="form-control mr-2 ml-2" style="width: auto;">
          @foreach([10, 20, 50, 100] as $num)
            <option @if(isset($record_per_page) && $record_per_page == $num) selected @endif>{{ $num }}</option>
          @endforeach
        </select>
      </div>

    </div>
  </div>
  <table id="example2" class="table table-bordered table-hover mb-5 mt-2">
    <thead>
    <tr>
      <th>{{ trans('messages.mails.sent_datetime') }}</th>
      <th>{{ trans('messages.mails.title') }}</th>
    </tr>
    </thead>
    <tbody>
    @if ( isset($mail_histories) && count($mail_histories) > 0 )
      @foreach ($mail_histories as $index => $mail_history)
        <tr>
          <td>{{ $mail_history->sent_datetime->format('Y/m/d H:i:s') }}</td>
          <td>
            <a href="#" class="mail-history-detail" data-index="{{ $index }}">
              {{ $mail_history->title }}
            </a>
          </td>
        </tr>
      @endforeach
    @else
      <tr>
        <td colspan="11" class="text-center">{{ trans('messages.no_record') }}</td>
      </tr>
    @endif

    </tbody>
  </table>
  {{ $mail_histories->appends(request()->only('record_per_page'))->links() }}
</div>
<script>
    (function ($) {
        /* ---------------------------------------------------
        // record per page change
        -----------------------------------------------------*/
        (function() {
            $('#record_per_page').change(function(){
                const recordPerPage = $(this).val();
                $.ajax({
                    url: '{{ route('customer.email.history', $customer_id) }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        record_per_page: recordPerPage,
                        page: 1
                    },
                    cache: false,
                    success: function (response) {
                        // to unbind event
                        $('.mail-history').children().remove();
                        $('.mail-history').append($(response.data));
                    }
                });
            });
        })();

        /* ---------------------------------------------------
        // mail history detail popup
        -----------------------------------------------------*/
        (function() {
            const mailsHistories = @json($mail_histories);
            const showDetail = function() {
                $('#email-history-modal').modal('show').one('hidden.bs.modal', function(){
                    $('.std-modal-box').modal('show');
                });
            };

            $('.mail-history-detail').click(function(event){
                event.preventDefault();
                event.stopPropagation();
                const index = $(this).data('index');
                const mailHistory = mailsHistories.data[index];
                $('#customer-email').html(mailHistory.email);
                $('#p-title').val(mailHistory.title);
                $('#p-contents').html(mailHistory.contents);

                $('.std-modal-box').modal('hide').one('hidden.bs.modal', function(){
                  showDetail();
                });

            });
        })();

        /* ---------------------------------------------------
        // pagination
        -----------------------------------------------------*/
        (function() {
            $('.mail-history .pagination a').click(function(event){
                event.preventDefault();
                event.stopPropagation();
                let url = $(this).attr('href');
                //url fix if not email-history url
                if (url.indexOf('email-history') == -1) {
                  url = url.replace('email', 'email-history');
                }
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    cache: false,
                    success: function (response) {
                        // to unbind event
                        $('.mail-history').children().remove();
                        $('.mail-history').append($(response.data));
                    }
                });
            });
        })();

    })(jQuery);


</script>