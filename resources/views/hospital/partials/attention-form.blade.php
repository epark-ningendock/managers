@php
use App\Enums\RegistrationDivision;
use App\Enums\HplinkContractType;

if(isset($hospital)) {
  $hospital_details = $hospital->hospital_details;
  $hospital_option_plans = $hospital->hospital_option_plans;

  $dr_movie = 0;
  $access_movie = 0;
  $one_min_movie = 0;
  $tour_movie = 0;
  $exam_movie = 0;
  $special_page = 0;
  $pay_per_use_price = 0;
  foreach ($hospital_option_plans as $hospital_option_plan) {
      if ($hospital_option_plan->option_plan_id == 1) {
        $dr_movie = 1;

      } elseif ($hospital_option_plan->option_plan_id == 2) {
        $access_movie = 2;
        $access_movie_price = $hospital_option_plan->price;
      } elseif ($hospital_option_plan->option_plan_id == 3) {
        $one_min_movie = 3;
        $one_min_movie_price = $hospital_option_plan->price;
      } elseif ($hospital_option_plan->option_plan_id == 4) {
        $tour_movie = 4;
        $tour_movie_price = $hospital_option_plan->price;
      } elseif ($hospital_option_plan->option_plan_id == 5) {
        $exam_movie = 5;
        $exam_movie_price = $hospital_option_plan->price;
      } elseif ($hospital_option_plan->option_plan_id == 6) {
        $special_page = 6;
        $pay_per_use_price = $hospital_option_plan->pay_per_use_price ?? 0;
      }
  }
 }

$o_minor_ids = collect(old('minor_ids'));
$o_minor_values = collect(old('minor_values'));

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
                  <div class="mt-4">
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
          <div class="col-md-12 mt-4">
            <div class="form-group py-sm-1 " style="margin-left: 0;">
              <legend>フリーエリア </legend>
              <textarea class="form-control minor-text" name="free_area" cols="30" rows="5">{{ $hospital->free_area }}</textarea>
              <p class="mt-1" style="color: #737373; font-size: 1.3rem;">※HTMLで記述することが可能です。</p>
              @if ($errors->has('free_area')) <p class="has-error" style="font-size: 1.3rem;">{{ $errors->first('free_area') }}</p> @endif
            </div>
          </div>
          <div class="col-md-12 mt-4">
            <div class="form-group py-sm-1 " style="margin-left: 0;">
              <legend>検索ワード </legend>
              <textarea class="form-control minor-text" name="search_word" cols="30" rows="5">{{ $hospital->search_word }}</textarea>
              <p class="mt-1" style="color: #737373; font-size: 1.3rem;">※検索する単語をカンマ(,)区切りで入力してください。</p>
              <p style="color: #737373; font-size: 1.3rem;">※HTMLで記述することが可能です。</p>
              @if ($errors->has('search_word')) <p class="has-error" style="font-size: 1.3rem;">{{ $errors->first('search_word') }}</p> @endif
            </div>
          </div>          
          <div class="col-md-12 mt-4">
            <div class="form-group py-sm-1 " style="margin-left: 0;">
              <legend>プラン</legend>
              <div class="form-group margin-none py-sm-1">
                <select name="contract_plan_id" id="contract_plan" class="form-control">
                  <option value="">プランを選択</option>
                  @foreach($contractPlans as $contract_plan)
                    <option value="{{ $contract_plan->id }}"
                            @if ( (old('contract_plan_id', (isset($hospital->hospital_plan->contract_plan_id) ) ? $hospital->hospital_plan->contract_plan_id : null) == $contract_plan->id ) )
                            selected="selected"
                            @endif
                    > {{ $contract_plan->plan_name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('contract_plan_id')) <p class="help-block">{{ $errors->first('contract_plan_id') }}</p> @endif
              </div>
            </div>
          </div>
          <div class="col-md-16 mt-4">

                  <legend>オプションプラン</legend>

              <div class="form-group margin-none py-sm-2">
                      <input type="hidden" name="dr_movie" value="0"/>
                      <input type="checkbox" id="dr_movie" name="dr_movie" value="1"  @if( $dr_movie == 1) checked @endif />
                      <label for="dr_movie">Dr.動画　（16,500円）</label>
                  　　<input class="form-control w8em" type="number" id="dr_movie_price" name="dr_movie_price" value="{{ old('dr_movie_price', (isset($dr_movie_price_price) ? $dr_movie_price_price : 0)) }}">円

              </div>

                  <div class="form-group margin-none py-sm-1">
                      <input type="hidden" name="access_movie" value="0"/>
                      <input type="checkbox" id="access_movie" class="option-plan-checkbox"  name="access_movie" value="2" @if($access_movie == 2) checked @endif />
                      <label for="access_movie">アクセス動画　（11,000円）</label>
                      <input class="form-control w8em" type="number" id="access_movie_price" name="access_movie_price" value="{{ old('access_movie_price', (isset($access_movie_price) ? $access_movie_price : 0)) }}">円
                  </div>
                  <div class="form-group margin-none py-sm-1">
                      <input type="hidden" name="one_min_movie" value="0"/>
                      <input type="checkbox" id="one_min_movie" class="option-plan-checkbox"  name="one_min_movie" value="3" @if( $one_min_movie == 3) checked @endif/>
                      <label for="one_min_movie">院内1分動画　（5,500円）</label>
                      <input class="form-control w8em" type="number" id="one_min_movie_price" name="one_min_movie_price" value="{{ old('one_min_movie_price', (isset($one_min_movie_price) ? $one_min_movie_price : 0)) }}">円
                  </div>
                  <div class="form-group margin-none py-sm-1">
                      <input type="hidden" name="tour_movie" value="0"/>
                      <input type="checkbox" id="tour_movie" class="option-plan-checkbox"  name="tour_movie" value="4" @if($tour_movie == 4) checked @endif/>
                      <label for="tour_movie">院内ツアー　（27,500円）</label>
                      <input class="form-control w8em" type="number" id="tour_movie_price" name="tour_movie_price" value="{{ old('tour_movie_price', (isset($tour_movie_price) ? $tour_movie_price : 0)) }}">円
                  </div>
                  <div class="form-group margin-none py-sm-1">
                      <input type="hidden" name="exam_movie" value="0"/>
                      <input type="checkbox" id="exam_movie" class="option-plan-checkbox" name="exam_movie" value="5" @if($exam_movie == 5) checked @endif/>
                      <label for="exam_movie">検査動画　（4,400円）</label>
                      <input class="form-control w8em" type="number" id="exam_movie_price" name="exam_movie_price" value="{{ old('exam_movie_price', (isset($exam_movie_price) ? $exam_movie_price : 0)) }}">円
                  </div>
                  <div class="form-group margin-none py-sm-1">
                      <input type="hidden" name="special_page" value="0"/>
                      <input type="checkbox" id="special_page" class="option-plan-checkbox" name="special_page" value="6" @if($special_page == 6) checked @endif/>
                      <label for="special_page">特集ページ　（1,100円）</label>
                      <label class="mr-2" for="pay_per_use_price" style="margin-left: 12px;">1回当たりの料金</label>
                      <input class="form-control w8em" type="number" id="pay_per_use_price" name="pay_per_use_price" value="{{ old('pay_per_use_price', (isset($pay_per_use_price) ? $pay_per_use_price : 0)) }}">円
                  </div>

          </div>
          <div class="col-md-12 mt-4">
            <div class="form-group py-sm-1 " style="margin-left: 0;">
              <legend>HPリンク</legend>
              <div class="form-group @if( $errors->has('hplink_contract_type'))  has-error @endif">
                <div class="radio">
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
                    <input type="number" name="hplink_count" id="hplink_count"
                          value="{{ old('hplink_count', ( old('hplink_contract_type', (isset($hospital->hplink_contract_type)) ? $hospital->hplink_contract_type : null) == HplinkContractType::PAY_PER_USE && isset($hospital->hplink_count) ) ? $hospital->hplink_count : null) }}" />回
                    <label class="mr-2" for="hplink_price_one" style="margin-left: 12px;">1回当たりの料金</label>
                    <input type="number" name="hplink_price_one" id="hplink_price_one"
                          value="{{ old('hplink_price_one', ( old('hplink_contract_type', (isset($hospital->hplink_contract_type)) ? $hospital->hplink_contract_type : null) == HplinkContractType::PAY_PER_USE && isset($hospital->hplink_price) ) ? $hospital->hplink_price : null) }}" />円
                  </div>
                  <div class="form-group mt-3">
                    <div class="ml-12 radio">
                      <input type="radio" name="hplink_contract_type" id="monthly_subscription"
                            value="{{ HplinkContractType::MONTHLY_SUBSCRIPTION }}"
                            @if( old('hplink_contract_type', (isset($hospital->hplink_contract_type)) ? $hospital->hplink_contract_type : null) == HplinkContractType::MONTHLY_SUBSCRIPTION ) checked @endif>
                      <label class="radio-label" for="monthly_subscription"> {{ HplinkContractType::getDescription(2) }}</label>
                    </div>
                    <label class="mr-2" for="hplink_price_monthly">月額料金</label>
                    <input type="number" name="hplink_price_monthly" id="hplink_price_monthly"
                          value="{{ old('hplink_price_monthly', ( old('hplink_contract_type', (isset($hospital->hplink_contract_type)) ? $hospital->hplink_contract_type : null) == HplinkContractType::MONTHLY_SUBSCRIPTION && isset($hospital->hplink_price) ) ? $hospital->hplink_price : null) }}" />円
                  </div>
                </div>
                @if ($errors->has('hplink_contract_type')) <p class="help-block" style="text-align: center !important;">{{ $errors->first('hplink_contract_type') }}</p> @endif
              </div>
            </div>
          </div>
          <div class="col-md-12 mt-4">
            <div class="form-group py-sm-1 " style="margin-left: 0;">
              <legend>事前決済</legend>
              <div class="form-group @if( $errors->has('is_pre_account'))  has-error @endif">
                <div class="ml-12 radio">
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

          // 初期表示用
          if ($('#none').prop("checked")) {
            $('#hplink_count').prop('disabled', true);
            $('#hplink_price_one').prop('disabled', true);
            $('#hplink_price_monthly').prop('disabled', true);
          }
          if ($('#pay_per_use').prop("checked")) {
            $('#hplink_price_monthly').prop('disabled', true);
            $('#hplink_count').prop('disabled', false);
            $('#hplink_price_one').prop('disabled', false);
          }
          if ($('#monthly_subscription').prop("checked")) {
            $('#hplink_count').prop('disabled', true);
            $('#hplink_price_one').prop('disabled', true);
            $('#hplink_price_monthly').prop('disabled', false);
          }

          // 値が変わった時用
          $('#none').change(function() {
            if ($('#none').prop("checked")) {
              $('#hplink_count').prop('disabled', true);
              $('#hplink_price_one').prop('disabled', true);
              $('#hplink_price_monthly').prop('disabled', true);
            }
          });
          $('#pay_per_use').change(function() {
            if ($('#pay_per_use').prop("checked")) {
              $('#hplink_price_monthly').prop('disabled', true);
              $('#hplink_count').prop('disabled', false);
              $('#hplink_price_one').prop('disabled', false);
            }
          });
          $('#monthly_subscription').change(function() {
            if ($('#monthly_subscription').prop("checked")) {
              $('#hplink_count').prop('disabled', true);
              $('#hplink_price_one').prop('disabled', true);
              $('#hplink_price_monthly').prop('disabled', false);
            }
          });

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

#hplink_count:disabled {
  background: #eee;
}

#hplink_price_one:disabled {
  background: #eee;
}

#hplink_price_monthly:disabled {
  background: #eee;
}
</style>

@include('commons.datepicker')