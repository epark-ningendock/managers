@php
  use App\Station;
  use App\Rail;
@endphp
<div class="form-entry">
    <div class="box-body staff-form">
      <h2>基本情報</h2>
      <input type="hidden" name="lock_version" value="{{ $hospital->lock_version or ''}}"/>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group py-sm-1 " style="margin-left: 0;">
            <legend>状態</legend>
              <div class="form-group @if( $errors->has('status'))  has-error @endif">
                <div class="radio">
                  <label class="ml-5">
                    <input type="radio" name="status" id="private_status"
                           value="{{ \App\Enums\HospitalEnums::PRIVATE }}"
                           @if( old('status', (isset($hospital->status)) ? $hospital->status : null) == \App\Enums\HospitalEnums::PRIVATE ) checked @endif>
                    {{ \App\Enums\HospitalEnums::getDescription('0') }}
                  </label>

                  <label class="ml-3">
                    <input type="radio" name="status" id="public_status"
                           value="{{ \App\Enums\HospitalEnums::PUBLIC }}"
                           @if( old('status', (isset($hospital->status)) ? $hospital->status : null) == \App\Enums\HospitalEnums::PUBLIC ) checked @endif>
                    {{ \App\Enums\HospitalEnums::getDescription('1') }}
                  </label>

                  <label class="ml-3">
                    <input type="radio" name="status" id="deleted_status"
                           value="{{ \App\Enums\HospitalEnums::DELETE }}"
                           @if( old('status', (isset($hospital->status)) ? $hospital->status : null) == \App\Enums\HospitalEnums::DELETE ) checked @endif>
                    {{ \App\Enums\HospitalEnums::getDescription('X') }}
                  </label>
                </div>
                @if ($errors->has('status')) <p class="help-block" style="text-align: center !important;">{{ $errors->first('status') }}</p> @endif
              </div>
          </div>
        </div>
      </div>
      <!--医療機関-->
      <div class="row">
        <div class="col-md-12">
          <legend>医療機関</legend>
        </div>
        <div class="col-md-6">
          <div class="form-group margin-none py-sm-1 @if ($errors->has('name')) has-error @endif">
            <label for="name">{{ trans('messages.name') }}
              <span class="form_required">必須</span>
            </label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name', (isset($hospital->name) ) ? $hospital->name : null) }}"
                   placeholder="{{ trans('messages.name') }}">
            @if ($errors->has('name')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('name') }}</p> @endif
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group margin-none py-sm-1 @if( $errors->has('kana'))  has-error @endif">
            <label for="kana">{{ trans('messages.kana') }}
              <span class="form_required">必須</span>
            </label>
            <input type="text" class="form-control" id="kana" name="kana"
                   value="{{ old('kana', (isset($hospital->kana) ) ? $hospital->kana : null) }}"
                   placeholder="{{ trans('messages.kana') }}">
            @if ($errors->has('kana')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('kana') }}</p> @endif
            <p class="form-support-txt">※並べ替えに使用します。 「医療法人」などの法人名を除いて下さい</p>
          </div>
        </div>

      </div>
      <!--//医療機関-->

      <!--所在地-->
      <div class="row mt-5">
        <div class="col-md-12">
          <legend>所在地</legend>
          <p class="form-support-txt">下記住所でGoogleMapが正しい位置を示さない場合、北緯と東経を入力して下さい。</p>
        </div>

        <div class="col-md-12">
          <div class="wrapbox">
            <div class="form-inline">
              <div class="form-group @if( $errors->has('postcode'))  has-error @endif">
                <label for="postcode"> 〒</label>

                  <span class="p-country-name" style="display:none;">Japan</span>
                  <input type="text" class="form-control" id="postcode1" name="postcode"
                         value="{{ old('postcode', (isset($hospital->postcode) ) ? $hospital->postcode : null) }}"
                         placeholder="1000005"/>
                  <input type="hidden" name="postcode" id="postcode" class="p-postal-code" size="8"
                         value="{{ old('postcode', (isset($hospital->postcode) ) ? $hospital->postcode : null) }}"/>

              <button type="button" class="btn btn-default" id="postcode-search">
                <img width="20px;" src="{{ asset('images/search.png') }}" alt="">
                {{ __('アドレス検索') }}
              </button>
              </div>
              @if ($errors->has('postcode')) <p class="help-block text"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('postcode') }}</p> @endif
            </div>
            <br/>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group margin-none py-sm-1  @if( $errors->has('prefecture_id'))  has-error @endif">
                  <label for="prefecture">{{ trans('messages.prefectures') }}</label>
                  <div class="form-group margin-none py-sm-1">
                    <select name="prefecture_id" id="prefecture" class="form-control p-region-id">
                      <option value="">都道府県を選択</option>
                      @foreach($prefectures as $prefecture)
                        <option value="{{ $prefecture->id }}"
                                @if ( (old('prefecture_id', (isset($hospital->prefecture_id) ) ? $hospital->prefecture_id : null) == $prefecture->id ) )
                                selected="selected"
                                @endif
                        > {{ $prefecture->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('prefecture_id')) <p class="help-block">{{ $errors->first('prefecture_id') }}</p> @endif
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group margin-none py-sm-1 @if( $errors->has('district_code'))  has-error @endif">
                  <label for="district_code">{{ trans('messages.district_code') }}</label>
                  <div>
                    <input type="hidden" class="p-locality" id="hidden-p-locality"></input>
                    <select name="district_code_id" id="district_code_id" class="form-control">
                      <option value="" id="district_init">市町村区を選択</option>
                      @foreach($district_codes as $district_code)
                        <option data-prefecture_id="{{ $district_code->prefecture_id }}"
                                value="{{ $district_code->id }}"
                                @if ( old('prefecture_id', (isset($hospital->prefecture_id) ) ? $hospital->prefecture_id : null) == $district_code->prefecture_id )
                                style="display: block;"
                                @endif
                                @if ( ($district_code->id == old('district_code_id', ( isset($hospital->district_code_id) ) ? $hospital->district_code_id : null )) )
                                selected="selected"
                                @endif
                        > {{ $district_code->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('district_code_id')) <p class="help-block">{{ $errors->first('district_code_id') }}</p> @endif
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-8">
                <div class="form-group margin-none py-sm-1 @if( $errors->has('address1'))  has-error @endif">
                  <label for="address1">{{ trans('messages.address') }} </label>
                  <div>
                    <input type="text" class="form-control p-street-address" id="address1" name="address1"
                           value="{{ old('address1', (isset($hospital->address1)) ? $hospital->address1 : null) }}"/>
                    @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
                  </div>
                </div>

                <div class="form-group margin-none py-sm-1 @if( $errors->has('address2'))  has-error @endif">
                  <label for="address2">{{ trans('messages.building_name') }} </label>
                  <div>
                    <input type="text" class="form-control p-extended-address" id="address2" name="address2"
                           value="{{ old('address2', (isset($hospital->address2)) ? $hospital->address2 : null) }}"/>
                    @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
                  </div>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group margin-none py-sm-1 @if( $errors->has('latitude'))  has-error @endif">
                  <label for="latitude">{{ trans('messages.latitude') }} </label>
                  <div>
                    <input type="text" class="form-control" id="latitude" name="latitude"
                           value="{{ old('latitude', (isset($hospital->latitude)) ? $hospital->latitude : null) }}"
                           placeholder="0.0000000"/>
                    @if ($errors->has('latitude')) <p class="help-block">{{ $errors->first('latitude') }}</p> @endif
                  </div>
                </div>

                <div class="form-group margin-none py-sm-1 @if( $errors->has('longitude'))  has-error @endif">
                  <label for="longitude">{{ trans('messages.longitude') }} </label>
                  <div>
                    <input type="text" class="form-control" id="longitude" name="longitude"
                           value="{{ old('longitude', (isset($hospital->longitude)) ? $hospital->longitude : null) }}"
                           placeholder="0.0000000"/>
                    @if ($errors->has('longitude')) <p class="help-block">{{ $errors->first('longitude') }}</p> @endif
                  <p class="form-support-txt">※北緯・東経は整数11桁、小数点以下7桁（マイナス記号、小数点含めて20文字）まで入力可能です。<br>※例）-122345678901.1234567</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="street-view-wrapper">
              <div class="form-group margin-none py-sm-1 @if( $errors->has('streetview_url'))  has-error @endif">
                <label for="streetview_url" class="">{{ trans('messages.streetview_url') }} </label>
                <div class="">
                  <input type="text" class="form-control" id="streetview_url" name="streetview_url"
                         value="{{ old('streetview_url', (isset($hospital->streetview_url)) ? $hospital->streetview_url : null ) }}"
                         placeholder="http://google.com/maps/~"/>
                  @if ($errors->has('streetview_url')) <p class="help-block">{{ $errors->first('streetview_url') }}</p> @endif
                </div>
              </div>
            </div>
          </div>
        </div>



        </div>
      <!--//所在地-->
      <!--電話番号-->
      <div class="row mt-5">
        <div class="col-md-12">
          <legend>{{ trans('messages.contact_information') }} </legend>
        </div>
        <div class="col-md-6">
          <div class="form-group margin-none py-sm-1 @if ($errors->has('tel')) has-error @endif">
            <label for="tel">{{ trans('messages.tel') }}
              <span class="form_required">必須</span>
            </label>
            <input type="text" class="form-control" id="tel" name="tel"
                   value="{{ old('tel', (isset($hospital->tel) ) ? $hospital->tel : null) }}"
                   placeholder="{{ trans('messages.tel') }}">
            @if ($errors->has('tel')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('tel') }}</p> @endif
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group margin-none py-sm-1 @if( $errors->has('paycall'))  has-error @endif">
            <label for="paycall">{{ trans('messages.paycall') }}
            </label>
            <input type="text" class="form-control" id="paycall" name="paycall"
                   value="{{ old('paycall', (isset($hospital->paycall) ) ? $hospital->paycall : null) }}"
                   placeholder="{{ trans('messages.paycall') }}">
            @if ($errors->has('paycall')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('paycall') }}</p> @endif
          </div>
        </div>
      </div>
      <!--//電話番号-->
      <div class="row">
        @for($i = 1; $i<= 5; $i++)
        <div class="col-md-12 mt-5">
          <legend>最寄り駅 {{ $i }} </legend>
        </div>
          <div class="col-md-4">
            <div class="form-group ml-0 mr-0">
              <select id="rail{{$i}}" name="rail{{$i}}" class="custom-select form-control">
                <option value="" id="init-rail{{$i}}">路線を選択</option>
                @if (!old('prefecture_id'))
                  @foreach($rails as $rail)
                    @if (!isset($rail)) @continue @endif
                    <option value="{{ $rail->id }}" id="rail-{{ $rail->id }}"
                            @if ( old('rail' . $i, (isset($hospital->{'rail'. $i})) ? $hospital->{'rail'. $i} : null) == $rail->id)
                            selected="selected"
                            @endif
                    >{{ $rail->name }}
                    </option>
                  @endforeach
                @else
                  @foreach(Rail::find(old('prefecture_id'))->get() as $rail)
                    @if (!isset($rail)) @continue @endif
                    <option value="{{ $rail->id }}" id="rail-{{ $rail->id }}"
                            @if ( old('rail' . $i, (isset($hospital->{'rail'. $i})) ? $hospital->{'rail'. $i} : null) == $rail->id)
                            selected="selected"
                            @endif
                    >{{ $rail->name }}
                    </option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group ml-0 mr-0 @if ($errors->has("station{$i}")) has-error @endif">
              <select id="station{{$i}}" name="station{{$i}}" class="custom-select form-control">
                <option value="" id="init-station{{$i}}">駅を選択</option>
                @if (!old('rail' . $i))
                  @foreach($five_stations[$i - 1] as $station)
                    @if (!isset($station)) @continue @endif
                    <option value="{{ $station->id }}" id="station-{{ $station->id }}"
                            @if ( old('station' . $i, (isset($hospital->{'station'. $i})) ? $hospital->{'station'. $i} : null) == $station->id)
                            selected="selected"
                            @endif
                    >{{ $station->name }}
                    </option>
                  @endforeach
                @else
                  @foreach(Station::find(old('rail' . $i))->get() as $station)
                    @if (!isset($station)) @continue @endif
                    <option value="{{ $station->id }}" id="station-{{ $station->id }}"
                            @if ( old('station' . $i, (isset($hospital->{'station'. $i})) ? $hospital->{'station'. $i} : null) == $station->id)
                            selected="selected"
                            @endif
                    >{{ $station->name }}
                    </option>
                  @endforeach
                @endif
              </select>
              @if ($errors->has("station{$i}")) <p class="help-block text"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first("station{$i}") }}</p>
              @endif
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group ml-0 mr-0 @if ($errors->has("access{$i}")) has-error @endif">
              <input type="text" class="form-control" id="access{{$i}}" name="access{{$i}}" placeholder="A4出口から、徒歩5分"
                     value="{{ old("access{$i}", (isset($hospital->{'access'. $i})) ? $hospital->{'access'. $i} : null) }}"/>
              @if ($errors->has("access{$i}")) <p class="help-block text"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first("access{$i}") }}</p>
              @endif
            </div>
          </div>
        @endfor
      </div>
    </div>

  <div class="box-body consultation-hours">
    <h2>休診日・診療時間</h2>
    <div class="wrapbox" style="padding: 20px;">
      <table class="table table-bordered">
        @for($i= 1; $i<= 4; $i++)
          <tr class="timebox">
            <td>
              診療時間 {{  $i }}
            </td>
            <td>
              @if ( isset($medical_treatment_times[$i-1]) && !empty($medical_treatment_times[$i-1]) )
                <div class="checkbox icheck">
                  <input type="hidden" name="medical_treatment_time[{{$i}}][id]"
                         value="{{ $medical_treatment_times[$i-1]->id }}">
                </div>
              @endif
              <div class="row">
                <div class="col-md-4 start-time-box">
                  <div class="form-group  @if( $errors->has("medical_treatment_time." .$i. ".start")) has-error @endif">
                    <label for="start-time-{{ $i }}"
                           class="start-time col-md-3"></label>
                    <div class="col-md-9">
                      <input size="5" type="text" class="form-control time-picker"
                             id="start-time-{{ $i }}" name="medical_treatment_time[{{$i}}][start]"
                             value="{{ old('medical_treatment_time.' . $i . '.start', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->start : null)) }}"
                      />
                      @if( $errors->has("medical_treatment_time." .$i. ".start"))
                        <p class="help-block">{{ $errors->first("medical_treatment_time." .$i. ".start") }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group   @if( $errors->has("medical_treatment_time." .$i. ".end")) has-error @endif">
                    <label for="end-time-{{$i}}" class="end-time col-md-3">〜</label>
                    <div class="col-md-9">
                      <input size="5" type="text" class="form-control time-picker"
                             id="end-time-{{$i}}" name="medical_treatment_time[{{$i}}][end]"
                             value="{{ old('medical_treatment_time.' . $i . '.end', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->end : null)) }}"
                      />
                      @if( $errors->has("medical_treatment_time." .$i. ".end"))
                        <p class="help-block">{{ $errors->first("medical_treatment_time." .$i. ".end") }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="daybox">
                <label class="checkbox-inline">
                  <input type="checkbox" name="medical_treatment_time[{{$i}}][mon]" value="1"
                         @if ( old('medical_treatment_time.' . $i . '.mon', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->mon : null) ) == 1 ) checked @endif>{{
                  trans('messages.mon') }}
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="medical_treatment_time[{{$i}}][tue]" value="1"
                         @if ( old('medical_treatment_time.' . $i . '.tue', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->tue : null)) == 1 ) checked @endif> {{ trans('messages.tue') }}
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="medical_treatment_time[{{$i}}][wed]" value="1"
                         @if ( old('medical_treatment_time.' . $i . '.wed', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->wed : null)) == 1 ) checked @endif> {{ trans('messages.wed') }}
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="medical_treatment_time[{{$i}}][thu]" value="1"
                         @if ( old('medical_treatment_time.' . $i . '.thu', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->thu : null)) == 1 ) checked @endif> {{ trans('messages.thu') }}
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="medical_treatment_time[{{$i}}][fri]" value="1"
                         @if ( old('medical_treatment_time.' . $i . '.fri', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->fri : null)) == 1 ) checked @endif> {{ trans('messages.fri') }}
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="medical_treatment_time[{{$i}}][sat]" value="1"
                         @if ( old('medical_treatment_time.' . $i . '.sat', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->sat : null)) == 1 ) checked @endif> {{ trans('messages.sat') }}
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="medical_treatment_time[{{$i}}][sun]" value="1"
                         @if ( old('medical_treatment_time.' . $i . '.sun', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->sun : null)) == 1 ) checked @endif> {{ trans('messages.sun') }}
                </label>
              </div>
            </td>
          </tr>
        @endfor
      </table>

      <div class="form-group @if( $errors->has('consultation_note'))  has-error @endif">
        <label for="consultation_note" class="col-md-2">{{ trans('messages.consultation_note') }} </label>
        <div class="col-md-10">
            <input type="text" placeholder="例）年末年始は休業" name="consultation_note" id="consultation_note" class="form-control" value="{{ old('consultation_note',(isset($hospital->consultation_note)) ? $hospital->consultation_note : null) }}">
          @if ($errors->has('consultation_note')) <p class="help-block">{{ $errors->first('consultation_note') }}</p> @endif
        </div>
      </div>

      <div class="form-group @if( $errors->has('memo'))  has-error @endif">
        <label for="memo" class="col-md-2">備考</label>
        <div class="col-md-10">
            <textarea name="memo" id="memo" rows="5"
                      class="form-control">{{ old('memo', (isset($hospital->memo)) ? $hospital->memo : null) }}</textarea>
          @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
          <p class="form-support-txt">※HTMLで記述することが可能です。</p>
        </div>
      </div>


      <!--医療機関-->
      <div class="row">
        <div class="col-md-12">
          <legend>代表者</legend>
        </div>
        <div class="col-md-6">
          <div class="form-group margin-none py-sm-1 @if ($errors->has('principal')) has-error @endif">
            <label for="name">名前</label>
            <input type="text" class="form-control" id="principal" name="principal"
                   value="{{ old('name', (isset($hospital->principal) ) ? $hospital->principal : null) }}"
                   placeholder="代表者名">
            @if ($errors->has('principal')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('principal') }}</p> @endif
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group margin-none py-sm-1 @if( $errors->has('principal_history'))  has-error @endif">
            <label for="history">略歴</label>
            <textarea name="principal_history" id="principal_history" rows="5"
                      class="form-control">{{ old('principal_history',(isset($hospital->principal_history)) ? $hospital->principal_history : null) }}</textarea>

            @if ($errors->has('principal_history')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('principal_history') }}</p> @endif
          </div>
        </div>

      </div>
      <!--//医療機関-->



      </div>

      <div class="col-md-12 mt-5">
        <legend>{{ trans('messages.examination_system_name') }}</legend>
        <select name="medical_examination_system_id" id="medical_examination_system_id" class="form-control w20em">
          <option value=""></option>
          @foreach($medical_examination_systems as $medical_examination_system)
            <option value="{{ $medical_examination_system->id }}"
                    @if ($medical_examination_system->id == old('medical_examination_system_id', (isset($hospital->medical_examination_system_id)) ? $hospital->medical_examination_system_id : null))
                    selected="selected"
                    @endif
            > {{ $medical_examination_system->name }}</option>
          @endforeach
        </select>
        @if ($errors->has('medical_examination_system_id')) <p
                class="help-block">{{ $errors->first('medical_examination_system_id') }}</p> @endif

        <legend>{{ trans('messages.kenshin_sys_hospital_id') }}</legend>
        <div class="col-md-4">
          <input type="text" name="kenshin_sys_hospital_id" id="kenshin_sys_hospital_id" class="form-control" value="{{ old('kenshin_sys_hospital_id',(isset($hospital->kenshin_sys_hospital_id)) ? $hospital->kenshin_sys_hospital_id : null) }}">
          @if ($errors->has('kenshin_sys_hospital_id')) <p class="help-block">{{ $errors->first('kenshin_sys_hospital_id') }}</p> @endif
        </div>
      </div>

    </div>
  </div>

</div>
<div class="col-md-12 pb-5">
  <button type="submit" class="btn btn-primary pull-right">保存</button>
</div>
<br/>

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">

  <style>
    p.help-block {
      text-align: left;
    }

    select#district_code_id option {
      display: none;
    }
  </style>
@endpush

@includeIf('commons.timepicker')

@push('js')
  <script src="{{ asset('js/yubinbango.js') }}" charset="UTF-8"></script>
  <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}"></script>

  <script>
    (function ($) {

      /**
       * 都道府県に紐付く市区町村を取得し、SelectBoxにセットする
       * @param 都道府県のOption value
       */
      function distict_code_selector($option_id) {
        let all_district_code_option = $('#district_code_id option');
        // hide() して select box の中身を初期化
        all_district_code_option.hide();
        if (!$option_id) {
          $('#district_code_id option:selected').attr('selected', false);
          $('#district_init').attr('selected', true);
          $('#district_init').show();
          return;
        }
        let district_code_options = $('select#district_code_id option[data-prefecture_id="' + $option_id + '"]');
        // yubinbango.js で取得できた市区町村と一致した市区町村の文字列をセレクトする
        let matched_option;
        for (var i = 0; i < district_code_options.length; i++) {
          if (district_code_options.eq(i).text().trim() == $('#hidden-p-locality').val()) {
            matched_option = district_code_options.eq(i);
          }
        }
        if (matched_option) {
          matched_option.first().attr('selected', true);
        } else {
          district_code_options.first().attr('selected', true);
        }
        district_code_options.show();
      }

      /**
       * 都道府県から、該当の線路をプルダウンにセットする
       * @param 都道府県ID
       */
      function rail_selector(prefecture_id) {
        $.ajax({
          url: "{{ route('hospital.find-rails') }}",
          type: "POST",
          data: {
            _token: '{{ csrf_token() }}',
            prefecture_id: prefecture_id
          }
        })
        .done(function(data) {
          $('option[id^="station-"]').remove();
          $('option[id^="init-station"]').attr('selected', true);

          $('option[id^="rail-"]').remove();
          options = [];
          $.each(data.data, function (i, rail) {
            $option = $('<option>', { value: rail.id, text: rail.name, id: 'rail-' + rail.id});
            options.push($option);
          });
          $('select[id^="rail"]').append(options);
          $('option[id^="init-rail"]').attr('selected', true);
        })
        .fail(function(data) {
          console.log('fail');
          console.log(JSON.stringify(data.data));
        });
      }

      /**
       * 都道府県から、該当の線路をプルダウンにセットする
       * @param 都道府県ID
       */
      function station_selector(rail_id, dom_name) {
        dom_index = dom_name.replace('rail', '');
        $.ajax({
          url: "{{ route('hospital.find-stations') }}",
          type: "POST",
          data: {
            _token: '{{ csrf_token() }}',
            rail_id: rail_id
          }
        })
        .done(function(data) {
          $('select[id=station' + dom_index + '] option').remove();
          // プルダウンの中身全て remove したので、初期値を追加＆選択
          $init_station_option = $('<option>', { value: "", text: "駅を選択", id: 'init-station' + dom_index});
          $('select[id=station' + dom_index + ']').append($init_station_option);
          $('option[id=init-station' + dom_index + ']').attr('selected', true);

          options = [];
          $.each(data.data, function (i, station) {
            $option = $('<option>', { value: station.id, text: station.name, id: 'station-' + station.id});
            options.push($option);
          });
          $('select[id=station' + dom_index + ']').append(options);
        })
        .fail(function(data) {
          //取得できなければ駅を初期値にする
          $('select[id=station' + dom_index + '] option').remove();
          $init_station_option = $('<option>', { value: "", text: "駅を選択", id: 'init-station' + dom_index});
          $('select[id=station' + dom_index + ']').append($init_station_option);
          console.log('fail');
          console.log(JSON.stringify(data.data));
        });
      }

      /**
       * 入力フォームの住所を取得し、returnする
       * @return 住所
       */
      function getAddress() {
        return $('#prefecture option:selected').text() + $('#district_code_id option:selected').text() + $('#address1').val() + $('#address2').val()
      }

      /**
       * 北緯東経を取得し、入力フォームにセットする
       * @param 検索住所
       */
      function setLatLng(address) {
        let geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function(results, status){
          if(status == google.maps.GeocoderStatus.OK) {
            let lat = results[0].geometry.location.lat();
            let lng = results[0].geometry.location.lng();
            $('#latitude').val(lat.toFixed(7));
            $('#longitude').val(lng.toFixed(7));
          }
        });
      }

      $('#prefecture, #district_code_id, #address1, #address2')
        .focusin(e => {
          setLatLng(getAddress())
        })
        .focusout(e => {
          setLatLng(getAddress())
        });

      /* ---------------------------------------------------
      Icheck
      -----------------------------------------------------*/
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });

      /* ---------------------------------------------------
       combine postcode before submit
      -----------------------------------------------------*/
      $('#postcode-search').click(function (event) {
          event.preventDefault();
          event.stopPropagation();
          $('#postcode').val($('#postcode1').val().replace(/-/g , ""));
          //to trigger native keyup event
          $('#postcode')[0].dispatchEvent(new KeyboardEvent('keyup', {'key': ''}));

          //select distict code id
          setTimeout(function () {
            distict_code_selector($('#prefecture').val());
            // 都道府県が変わらなかった場合は、路線情報リセットしないようにしたい
            // が前回の都道府県情報を持つことが難しい and 都道府県が変わった時だけ
            // イベントを発火ができなかったので、今回は見送り
            rail_selector($('#prefecture').val());
            setLatLng(getAddress());
          }, 500);
      });

      /* ---------------------------------------------------
      Select district_code_id by prefecture id
      -----------------------------------------------------*/
      $(document).on('change', '#prefecture', function () {
        distict_code_selector($(this).val());
        rail_selector($(this).val());
      });

      /* ---------------------------------------------------
      路線に応じた駅をプルダウンにセットする
      -----------------------------------------------------*/
      $(document).on('change', "[id^=rail]", function () {
          console.log($(this).val());
        if($(this).val()) {
          station_selector($(this).val(), $(this).attr('name'));
        } else {
          console.log($(this).attr('name'));
          station_selector('', $(this).attr('name'));
        }
      });

    })(jQuery);
  </script>
@endpush
