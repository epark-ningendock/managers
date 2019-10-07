@php
use App\FeeRate;
use App\Enums\RegistrationDivision;
use App\Enums\HplinkContractType;

if(isset($hospital)) {
  $hospital_details = $hospital->hospital_details;
}

$o_minor_ids = collect(old('minor_ids'));
$o_minor_values = collect(old('minor_values'));

// 通常手数料
$o_fee_rate_ids = collect(old('fee_rate_ids'));
$o_rates = collect(old('rates'));
$o_from_dates = collect(old('from_dates'));
$fee_rate_count = $o_fee_rate_ids->isNotEmpty() ? $o_fee_rate_ids->count() : $feeRates->count();

// 事前決済手数料
$o_pre_payment_fee_rate_ids = collect(old('pre_payment_fee_rate_ids'));
$o_pre_payment_rates = collect(old('pre_payment_rates'));
$o_pre_payment_from_dates = collect(old('pre_payment_from_dates'));
$pre_payment_fee_rate_count = $o_pre_payment_fee_rate_ids->isNotEmpty() ? $o_pre_payment_fee_rate_ids->count() : $prePaymentFeeRates->count();

@endphp
<div class="form-entry">
  <div class="box-body">
    <h2>こだわり情報</h2>
      <div class="row">
          <div class="col-md-12">
              <div class="form-group py-sm-1 " style="margin-left: 0;">
                  <legend>PV・予約 </legend>
                  <p>PV件数
                      <span>{{ $hospital->pv_count }}</span> 件
                  </p>
                  <label for="pvad">PR</label>
                  <input class="form-control w8em" type="number" id="pvad" name="pvad" value="{{ old('pvad', (isset($hospital->pvad) ? $hospital->pvad : 0)) }}">
                  @if ($errors->has('pvad')) <p class="has-error">{{ $errors->first('pvad') }}</p> @endif
                  <div class="mt-5">
                    <input type="hidden" name="is_pickup" value="0" />
                    <input type="checkbox" name="is_pickup" id="is_pickup" value="1" @if(old('is_pickup', $hospital->is_pickup) == 1) checked @endif/>
                    <label for="is_pickup">ピックアップ</label>
                  </div>
              </div>
          </div>
          @foreach($middles as $middle)
          <div class="col-md-12 mt-4">
              @if(!isset($last) || $middle != $last)
                  <legend>{{ $middle->name }}</legend>
                  @php
                      $last = $middle
                  @endphp
              @endif
              <div>
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
                              $minor_value = $minor->is_fregist == RegistrationDivision::CHECK_BOX ? $temp[0]->select_status : $temp[0]->inputstring;
                            }
                          }
                      @endphp
                      <input type="hidden" name="minor_ids[]" value="{{ $minor->id }}" />
                      @if($minor->is_fregist == RegistrationDivision::CHECK_BOX)
                          <input type="checkbox" class="minor-checkbox" name="minor_values[]"
                                 id="{{ 'minor_id_'.$minor->id }}"
                                 {{ $minor_value == 1 ? 'checked' : '' }} value="1" />
                          <label class="mr-2" for="{{ 'minor_id_'.$minor->id }}">{{ $minor->name }}</label>
                      @elseif($minor->is_fregist == '2')
                          <div class="form-group mt-3">
                            <input type="checkbox" 
                              class="fregist-2-checkbox"
                              id="{{ 'minor_id_'.$minor->id }}" 
                              {{ $minor_value ? 'checked' : '' }}
                              value="{{ $minor->id }}" />
                            <label class="mr-2" for="{{ 'minor_id_'.$minor->id }}">{{ $minor->name }}</label> 
                            <input type="hidden" class="fregist-2-text-dummy" name="minor_values[]" disabled value="" />
                            <input type="text" class="fregist-2-text" name="minor_values[]" value="{{ $minor_value }}" />
                          </div>
                      @else
                          <div class="form-group mt-3">
                              <label>{{ $minor->name }}</label>
                              <textarea class="form-control minor-text @if ($index > 0) mt-2 @endif" name="minor_values[]" cols="30" rows="5">{{ $minor_value }}</textarea>
                          </div>
                      @endif
                  @endforeach
              </div>
          </div>
          @endforeach
          <div class="col-md-12 mt-5">
            <div class="form-group py-sm-1 " style="margin-left: 0;">
              <legend>フリーエリア </legend>
              <textarea class="form-control minor-text" name="free_area" cols="30" rows="5">{{ $hospital->free_area }}</textarea>
              <p class="mt-1" style="color: #737373; font-size: 1.3rem;">※HTMLで記述することが可能です。</p>
              @if ($errors->has('free_area')) <p class="has-error" style="font-size: 1.3rem;">{{ $errors->first('free_area') }}</p> @endif
            </div>
          </div>
          <div class="col-md-12 mt-5">
            <div class="form-group py-sm-1 " style="margin-left: 0;">
              <legend>検索ワード </legend>
              <textarea class="form-control minor-text" name="search_word" cols="30" rows="5">{{ $hospital->search_word }}</textarea>
              <p class="mt-1" style="color: #737373; font-size: 1.3rem;">※検索する単語をカンマ(,)区切りで入力してください。</p>
              <p style="color: #737373; font-size: 1.3rem;">※HTMLで記述することが可能です。</p>
              @if ($errors->has('search_word')) <p class="has-error" style="font-size: 1.3rem;">{{ $errors->first('search_word') }}</p> @endif
            </div>
          </div>          
        </div>
        <div class="col-md-12 mt-5">
          <div class="form-group py-sm-1 " style="margin-left: 0;">
            <legend>HPリンク</legend>
            <div class="form-group @if( $errors->has('hplink_contract_type'))  has-error @endif">
              <div class="radio">
                {{-- ml ってマージン？ --}}
                <div class="form-group mt-3">
                  <div class="ml-12 radio">
                    <input type="radio" name="hplink_contract_type" id="none"
                           value="{{ HplinkContractType::NONE }}"
                           @if( old('hplink_contract_type', (isset($hospital->hplink_contract_type)) ? $hospital->hplink_contract_type : null) == HplinkContractType::NONE ) checked @endif>
                    <label class="radio-label" for="none"> {{ HplinkContractType::getDescription(0) }}</label>
                  </div>
                </div>
                <div class="form-group mt-3">
                  <div class="ml-12 radio">
                    <input type="radio" name="hplink_contract_type" id="pay_per_use"
                           value="{{ HplinkContractType::PAY_PER_USE }}"
                           @if( old('hplink_contract_type', (isset($hospital->hplink_contract_type)) ? $hospital->hplink_contract_type : null) == HplinkContractType::PAY_PER_USE ) checked @endif>
                    <label class="radio-label" for="pay_per_use"> {{ HplinkContractType::getDescription(1) }}</label>
                  </div>
                  <label class="mr-2" for="hplink_count">無料の回数</label>
                  <input type="text" name="hplink_count"
                  value="{{ old('hplink_count', (isset($hospital->hplink_count) ) ? $hospital->hplink_count : null) }}" />回
                  <label class="mr-2" for="hplink_price">1回当たりの料金</label>
                  <input type="text" name="hplink_price"
                  value="{{ old('hplink_price', (isset($hospital->hplink_price) ) ? $hospital->hplink_price : null) }}" />円
                <div class="form-group mt-3">
                  <div class="ml-12 radio">
                    <input type="radio" name="hplink_contract_type" id="monthly_subscription"
                           value="{{ HplinkContractType::MONTHLY_SUBSCRIPTION }}"
                           @if( old('hplink_contract_type', (isset($hospital->hplink_contract_type)) ? $hospital->hplink_contract_type : null) == HplinkContractType::MONTHLY_SUBSCRIPTION ) checked @endif>
                    <label class="radio-label" for="monthly_subscription"> {{ HplinkContractType::getDescription(2) }}</label>
                  </div>
                  {{-- <label class="mr-2" for="hplink_price">月額料金</label>
                  <input type="text" name="hplink_price"
                  value="{{ old('hplink_price', (isset($hospital->hplink_price) ) ? $hospital->hplink_price : null) }}" />円 --}}
              </div>
              @if ($errors->has('hplink_contract_type')) <p class="help-block" style="text-align: center !important;">{{ $errors->first('hplink_contract_type') }}</p> @endif
            </div>
          </div>
        </div>
        <div class="col-md-12 mt-5">
          <div class="form-group py-sm-1 " style="margin-left: 0;">
            <legend>事前決済</legend>
            <div class="form-group @if( $errors->has('is_pre_account'))  has-error @endif">
              <div class="ml-12 radio">
                {{-- mt ってマージン？ --}}
                <div class="form-group mt-3">
                  <input type="radio" name="is_pre_account" value="0" id="is_pre_account_true"
                        @if( old('is_pre_account', (isset($hospital->is_pre_account)) ? $hospital->is_pre_account : null) == false ) checked @endif>
                  <label class="radio-label" for="is_pre_account_true">利用なし</label>
                </div>
                <div class="form-group mt-3">
                  <input type="radio" name="is_pre_account" value="1" id="is_pre_account_false"
                        @if( old('is_pre_account', (isset($hospital->is_pre_account)) ? $hospital->is_pre_account : null) == true ) checked @endif>
                  <label class="radio-label" for="is_pre_account_false">利用あり</label>
                </div>
              </div>
              @if ($errors->has('is_pre_account')) <p class="help-block" style="text-align: center !important;">{{ $errors->first('is_pre_account') }}</p> @endif
            </div>
          </div>
        </div>
          <div class="col-md-12 mt-5">
              <h2>手数料率</h2>
              <div class="form-group py-sm-1 " style="margin-left: 0;">
                  <div class="form-inline">
                      <label style="font-size: 1.2em; margin: 0 12px 0 0;">通常手数料</label><button type="button" class="btn btn-primary" id="add-fee-rate-button">追加</button>
                  </div>
                  <div id='fee-rate-block'>
                      @for($i = 0; $i < $fee_rate_count; $i++)
                        @php
                          if($o_fee_rate_ids->isNotEmpty()) {
                            $feeRate = new FeeRate([
                              'id' => $o_fee_rate_ids[$i],
                              'rate' => $o_rates[$i],
                              'from_date' => $o_from_dates[$i]
                            ]);
                          } else {
                            $feeRate = $feeRates[$i];
                          }
                        @endphp
                          <div class="form-group">
                              <div class="form-inline">
                                  <input type="hidden" name="fee_rate_ids[]" value="{{ $feeRate->id }}" />
                                  <label class="mt-5 ml-5">手数料率</label>
                                  <input type="number" class="form-control" id="{{ 'rate'.$feeRate->id }}" name="rates[]" value="{{ isset($feeRate->rate) ? $feeRate->rate : '' }}"> %
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
                      @endfor
                  </div>
                  @if ($errors->has('rate')) <p class="has-error">{{ $errors->first('rate') }}</p> @endif
                  @if ($errors->has('from_date')) <p class="has-error">{{ $errors->first('from_date') }}</p> @endif

                  <div class="form-inline">
                      <label style="font-size: 1.2em; margin: 0 12px 0 0;">事前決済手数料</label><button type="button" class="btn btn-primary" id="add-pre-payment-button">追加</button>
                  </div>

                  <div id='pre-payment-block'>
                    @for($i = 0; $i < $pre_payment_fee_rate_count; $i++)
                      @php
                        if($o_fee_rate_ids->isNotEmpty()) {
                          $prePaymentFeeRate = new FeeRate([
                            'id' => $o_pre_payment_fee_rate_ids[$i],
                            'rate' => $o_pre_payment_rates[$i],
                            'from_date' => $o_pre_payment_from_dates[$i]
                          ]);
                        } else {
                          $prePaymentFeeRate = $prePaymentFeeRates[$i];
                        }
                      @endphp
                          <div class="form-group">
                              <div class="form-inline">
                                  <input type='hidden' name='pre_payment_fee_rate_ids[]' value='{{ $prePaymentFeeRate->id }}' />
                                  <label class="mt-5 ml-5">手数料率</label>
                                  <input type="number" class="form-control" name="pre_payment_rates[]" value="{{ isset($prePaymentFeeRate->rate) ? $prePaymentFeeRate->rate : '' }}"> %
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
                      @endfor
                  </div>
                  @if ($errors->has('pre_payment_rate')) <p class="has-error">{{ $errors->first('pre_payment_rate') }}</p> @endif
                  @if ($errors->has('pre_payment_from_date')) <p class="has-error">{{ $errors->first('pre_payment_from_date') }}</p> @endif

              </div>
          </div>
      </div>
  </div>

  <div class="box-primary">
    <div class="box-footer">
      <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">保存</button>
    </div>
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
              + "<input class='ml-1 form-control' type='number' name='rates[]' value='' placeholder=''> %"
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
              + "<input class='ml-1 form-control' type='number' name='pre_payment_rates[]' value='' placeholder=''> %"
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

          let fregistTwoText = $(".fregist-2-text").val();

          $(".fregist-2-checkbox").ready(function(e) {
            fregistTwoText = setFregistTwoValue(fregistTwoText);
          });
          
          $(".fregist-2-checkbox").click(function(e) {
            fregistTwoText = setFregistTwoValue(fregistTwoText);
          });

          function setFregistTwoValue(value) {
            let newValue = value
            if ($('.fregist-2-checkbox:checked').val()) {
              $(".fregist-2-text").prop('disabled', false);
              $(".fregist-2-text-dummy").prop('disabled', true);
              $(".fregist-2-text").val(value);
            } else {
              newValue = $(".fregist-2-text").val();
              $(".fregist-2-text").prop('disabled', true);
              $(".fregist-2-text-dummy").prop('disabled', false);
              $(".fregist-2-text").val('');
            }
            return newValue;
          };

          /* ---------------------------------------------------
          // minor checkbox values
          -----------------------------------------------------*/
          (function () {
              const change = function(ele) {
                  if (ele.prop('checked')) {
                      if (ele.next().next().attr('class') == 'dummy') {
                        ele.next().next().remove();
                      }
                  } else {
                      $('<input type="hidden" class="dummy" name="minor_values[]" value="0"/>').insertAfter(ele.next('label'));
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