@php
if(isset($hospital)) {
  $hospital_details = $hospital->hospital_details;
}

$o_minor_ids = collect(old('minor_ids'));
$o_minor_values = collect(old('minor_values'));

// 通常手数料
$o_fee_rate_ids = collect(old('fee_rate_ids'));
$o_rates = collect(old('rates'));
$o_from_dates = collect(old('from_dates'));

// 事前決済手数料
$o_pre_payment_fee_rate_ids = collect(old('pre_payment_fee_rate_ids'));
$o_pre_payment_rates = collect(old('pre_payment_rates'));
$o_pre_payment_from_dates = collect(old('pre_payment_from_dates'));

@endphp
  <div class="box-body">
    <table class="table table-bordered">
      <tr>
        <td class='text-bold' colspan="3">PV・予約</td>
        <td colspan="3">
          <p>PV件数 
              <span>{{ $hospital->pv_count }}</span> 件
          </p>
          <label class="mt-5">PR</label>
          <input type="number" id="pvad" name="pvad" value="{{ isset($hospital->pvad) ? $hospital->pvad : 0 }}">
          @if ($errors->has('pvad')) <p class="has-error">{{ $errors->first('pvad') }}</p> @endif
          <div class="mt-5">
            {{ Form::hidden('is_pickup') }}
            {{ Form::checkbox('is_pickup', 1, $hospital->is_pickup, array('id'=>'is_pickup')) }}
            <label for="is_pickup">ピックアップ</label>
          </div>
        </td>
      </tr>
      @foreach($middles as $middle)
        <tr>
          @if(!isset($last) || $middle != $last)
            <td class='text-bold' colspan="3">{{ $middle->name }}</td>
            @php
              $last = $middle
            @endphp
          @endif
          <td>
            @foreach($middle->minors_with_fregist_order as $index => $minor)
              @php
                $minor_value = '';
                if($o_minor_ids->isNotEmpty()) {
                  $search_index = $o_minor_ids->search(function($m_id) use ($minor) {
                    return $m_id == $minor->id;
                  });
                  if ($search_index >= 0) {
                    $minor_value = $o_minor_values[$search_index];
                  }
                } else if (isset($hospital_details)) {
                  $temp = $hospital_details->where('minor_classification_id', $minor->id)->flatten(1);
                  if ($temp->isNotEmpty()) {
                    $minor_value = $minor->is_fregist == '1' ? $temp[0]->select_status : $temp[0]->inputstring;
                  }
                }
              @endphp
              <input type="hidden" name="minor_ids[]" value="{{ $minor->id }}" />
              @if($minor->is_fregist == '1')
                <input type="checkbox" class="minor-checkbox" name="minor_values[]"
                        id="{{ 'minor_id_'.$minor->id }}"
                        {{ $minor_value == 1 ? 'checked' : '' }} value="{{ $minor->id }}" />
                <label class="mr-2" for="{{ 'minor_id_'.$minor->id }}">{{ $minor->name }}</label>
              @else
              <div class="form-group mt-3">
                  <label>{{ $minor->name }}</label>
                  <textarea class="form-control minor-text @if ($index > 0) mt-2 @endif" name="minor_values[]" cols="30" rows="5">{{ $minor_value }}</textarea>
              </div>
              @endif
            @endforeach
          </td>
        </tr>
      @endforeach
      <tr>
        <td class='text-bold' colspan="3">手数料率</td>
        <td colspan="3">
          <div class="row mt-5">
            <div class="col-xs-6 col-lg-6"><p class="text-bold">通常手数料</p></div>
            <div class="col-xs-6 col-lg-6"><button type="button" class="btn btn-primary" id="add-fee-rate-button">追加</button></div>
          </div>
          <div id='fee-rate-block'>
            @foreach($feeRates as $feeRate)
              <div class="form-group">
                <div class="form-inline">
                  <input type="hidden" name="fee_rate_ids[]" value="{{ $feeRate->id }}" />
                  <label class="mt-5 ml-5">手数料率</label>
                  <input type="number" id="{{ 'rate'.$feeRate->id }}" name="rates[]" value="{{ isset($feeRate->rate) ? $feeRate->rate : 0 }}"> %
                  <label class="mt-5 ml-5">適用期間</label>
                  <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"
                        data-date-autoclose="true" data-date-language="ja">
                    <input type="text" class="form-control"
                            id="{{ 'from_date'.$feeRate->id }}" name="from_dates[]"
                            placeholder="yyyy-mm-dd" value="{{ isset($feeRate->from_date) ? $feeRate->from_date : '' }}">
                    <div class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                  </div>
                  <span class="ml-2 mr-2">~</span>
                </div>
              </div>
            @endforeach
          </div> 
          @if ($errors->has('rate')) <p class="has-error">{{ $errors->first('rate') }}</p> @endif
          @if ($errors->has('from_date')) <p class="has-error">{{ $errors->first('from_date') }}</p> @endif
          
          <div class="row mt-5">
            <div class="col-xs-6 col-lg-6"><p class="text-bold">事前決済手数料</p></div>
            <div class="col-xs-6 col-lg-6"><button type="button" class="btn btn-primary" id="add-pre-payment-button">追加</button></div>
          </div>
          <div id='pre-payment-block'>
            @foreach($prePaymentFeeRates as $prePaymentFeeRate)
              <div class="form-group">
                <div class="form-inline">
                  <input type='hidden' name='pre_payment_fee_rate_ids[]' value='{{ $prePaymentFeeRate->id }}' />
                  <label class="mt-5 ml-5">手数料率</label>
                  <input type="number" name="pre_payment_rates[]" value="{{ isset($prePaymentFeeRate->rate) ? $prePaymentFeeRate->rate : 0 }}"> %
                  <label class="mt-5 ml-5">適用期間</label>
                  <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"
                        data-date-autoclose="true" data-date-language="ja">
                    <input type="text" class="form-control"
                            name="pre_payment_from_dates[]"
                            placeholder="yyyy-mm-dd" value="{{ isset($prePaymentFeeRate->from_date) ? $prePaymentFeeRate->from_date : '' }}">
                    <div class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                  </div>
                  <span class="ml-2 mr-2">~</span>
                </div>
              </div>
            @endforeach
          </div>
          @if ($errors->has('pre_payment_rate')) <p class="has-error">{{ $errors->first('pre_payment_rate') }}</p> @endif
          @if ($errors->has('pre_payment_from_date')) <p class="has-error">{{ $errors->first('pre_payment_from_date') }}</p> @endif

        </td>
      </tr>
    </table>
  </div>

  <div class="box-primary">
    <div class="box-footer">
      <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">保存</button>
    </div>
  </div>

  @section('script')
  <script>
      (function ($) {
          let index = 0;
          $('#add-fee-rate-button').click(function(e) {
            index += 1;
            $('#fee-rate-block').append(
              "<div class='form-group'>"
              + "<div class='form-inline'>"
              + "<input type='hidden' name='fee_rate_ids[]' value='' />"
              + "<label class='mt-5 ml-5'>手数料率</label>"
              + "<input class='ml-1' type='number' name='rates[]' value='' placeholder=''> %"
              + "<label class='mt-5 ml-5'>適用期間</label>"
              + "<div class='input-group date ml-2' data-provide='datepicker' data-date-format='yyyy/mm/dd' data-date-autoclose='true' data-date-language='ja'>"
              + "<input type='text' class='form-control' name='from_dates[]' placeholder='yyyy/mm/dd' value=''>"
              + "<div class='input-group-addon'>"
              + "<span class='glyphicon glyphicon-calendar'></span>"
              + "</div>"
              + "</div>"
              + "<span class='ml-2 mr-2'>~</span>"
              + "</div>"
              + "</div>");
          });

          $('#add-pre-payment-button').click(function(e) {
            $('#pre-payment-block').append(
              "<div class='form-group'>"
              + "<div class='form-inline'>"
              + "<input type='hidden' name='pre_payment_fee_rate_ids[]' value='' />"
              + "<label class='mt-5 ml-5'>手数料率</label>"
              + "<input class='ml-1' type='number' name='pre_payment_rates[]' value='' placeholder=''> %"
              + "<label class='mt-5 ml-5'>適用期間</label>"
              + "<div class='input-group date ml-2' data-provide='datepicker' data-date-format='yyyy/mm/dd' data-date-autoclose='true' data-date-language='ja'>"
              + "<input type='text' class='form-control' name='pre_payment_from_dates[]' placeholder='yyyy/mm/dd' value=''>"
              + "<div class='input-group-addon'>"
              + "<span class='glyphicon glyphicon-calendar'></span>"
              + "</div>"
              + "</div>"
              + "<span class='ml-2 mr-2'>~</span>"
              + "</div>"
              + "</div>");
          });
          /* ---------------------------------------------------
          // minor checkbox values
          -----------------------------------------------------*/
          (function () {
              const change = function(ele) {
                  if (ele.prop('checked')) {
                      ele.next('input:hidden').remove();
                  } else {
                      $('<input type="hidden" name="minor_values[]" value="0"/>').insertAfter(ele.next('label'));
                  }
              };

              $('.minor-checkbox').each(function(index, ele) {
                  ele = $(ele);
                  ele.change(function() {
                      change(ele);
                  });
                  change(ele);
              });
          })();

      })(jQuery);
  </script>
@stop

<style>
.pr-input {
  width: 50%
}

tr, td {
  text-align: left !important;
}
</style>

@include('commons.datepicker')