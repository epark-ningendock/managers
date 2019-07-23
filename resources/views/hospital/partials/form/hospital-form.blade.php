<table class="table table-bordered mb-5 mt-5">
      <tr>
          <td class="gray-column">
              <label for="karada_dog_id">状態</label>
          </td>
          <td>
            <div class="form-group @if( $errors->has('status'))  has-error @endif">
                <div class="radio">
                    <label class="ml-5">
                        <input type="radio" name="status" id="private_status" value="{{ \App\Enums\HospitalEnums::Private }}" @if( old('status', (isset($hospital->status) ) ? $hospital->status : null) == \App\Enums\HospitalEnums::Private ) checked @endif>
                        {{ \App\Enums\HospitalEnums::getDescription('0') }}
                    </label>

                    <label class="ml-3">
                        <input type="radio" name="status" id="public_status" value="{{ \App\Enums\HospitalEnums::Public }}"  @if( old('status', (isset($hospital->status) ) ? $hospital->status : null) == \App\Enums\HospitalEnums::Public ) checked @endif>
                        {{ \App\Enums\HospitalEnums::getDescription('1') }}
                    </label>

                    <label class="ml-3">
                        <input type="radio" name="status" id="deleted_status" value="{{ \App\Enums\HospitalEnums::Delete }}"  @if( old('status', (isset($hospital->status) ) ? $hospital->status : null) == \App\Enums\HospitalEnums::Delete ) checked @endif>
                        {{ \App\Enums\HospitalEnums::getDescription('X') }}
                    </label>
                </div>
                @if ($errors->has('status')) <p class="help-block" style="text-align: center !important;">{{ $errors->first('status') }}</p> @endif
            </div>
          </td>
      </tr>

      <tr>
          <td class="gray-column">
              <label for="name">{{ trans('messages.names.hospital') }}</label>
          </td>
          <td>
              <div class="form-group @if( $errors->has('name'))  has-error @endif">
                  <label for="name" class="col-md-2">{{ trans('messages.name') }}</label>
                  <div class="col-md-10">
                      <input type="text" class="form-control" id="name" name="name"  value="{{ old('name', (isset($hospital->name) ) ? $hospital->name : null) }}" />
                      @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                  </div>
              </div>

              <div class="form-group @if( $errors->has('kana'))  has-error @endif">
                  <label for="name" class="col-md-2">{{ trans('messages.kana') }}</label>
                  <div class="col-md-10">
                      <input type="text" class="form-control" id="kana" name="kana"  value="{{ old('kana', (isset($hospital->kana) ) ? $hospital->kana : null) }}" />
                      @if ($errors->has('kana')) <p class="help-block">{{ $errors->first('kana') }}</p> @endif
                  </div>
              </div>

          </td>
      </tr>

    <tr>

        <td class="gray-column"><label for="">{{ __('所在地') }}</label></td>
        <td>

            <div class="wrapbox" style="padding: 20px;">

                <div class="form-inline">
                    <div class="form-group @if( $errors->has('postcode'))  has-error @endif">
                        <label for="postcode" class="col-md-4 text-right"> 〒</label>
                        <div class="col-md-8">
                            <span class="p-country-name" style="display:none;">Japan</span>
                            <input type="text" class="form-control" id="postcode1" name="postcode" value="{{ old('postcode', (isset($hospital->postcode) ) ? $hospital->postcode : null) }}" placeholder="8380222" />
                            <input type="hidden" name="postcode" id="postcode" class="p-postal-code" size="8" value="{{ old('postcode', (isset($hospital->postcode) ) ? $hospital->postcode : null) }}" />
                        </div>

                    </div>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <button type="button" class="btn btn-default" id="postcode-search" style="margin-left: 20px;">
                        <img width="20px;" src="{{ asset('images/search.png') }}" alt="">
                        {{ __('アドレス検索') }}
                    </button>
                    @if ($errors->has('postcode')) <p class="help-block text-danger" style="text-align: center; color: #dd4d3b;">{{ $errors->first('postcode') }}</p> @endif
                </div>

                <br/>


                <div class="row">

                    <div class="col-sm-6">

                        <div class="form-group @if( $errors->has('prefecture_id'))  has-error @endif">
                            <label for="prefecture" class="col-md-4">{{ trans('messages.prefectures') }}</label>
                            <div class="col-md-8">
                                <select name="prefecture_id" id="prefecture" class="form-control p-region-id">
                                    <option value="">都道府県を選択</option>
                                    @foreach($prefectures as $prefecture)
                                        <option value="{{ $prefecture->id }}"
                                                @if ( (old('prefecture_id') && ($prefecture->id == old('prefecture_id'))) ||  ( isset($hospital->prefecture_id) && $hospital->prefecture_id == $prefecture->id )  )
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

                        <div class="form-group @if( $errors->has('district_code'))  has-error @endif">
                            <div class="col-md-8">
                                <select name="district_code_id" id="district_code_id" class="form-control">
                                    <option value="">市町村区を選択</option>
                                    @foreach($district_codes as $district_code)
                                        <option data-prefecture_id="{{ $district_code->prefecture_id }}"
                                                value="{{ $district_code->id }}"
                                                @if (   old('prefecture_id', (isset($hospital->prefecture_id) ) ? $hospital->prefecture_id : null) == $district_code->prefecture_id )
                                                    style="display: block;"
                                                @endif
                                                @if (  ($district_code->id == old('district_code_id', ( isset($hospital->district_code_id) ) ? $hospital->district_code_id : null )) )
                                                selected="selected"
                                                @endif
                                        > {{ $district_code->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('district_code_id')) <p class="help-block">{{ $errors->first('district_code_id') }}</p> @endif
                            </div>
                            <label for="district_code" class="col-md-4">{{ trans('messages.district_code') }}</label>
                        </div>


                    </div>


                </div>

                <div class="row">

                    <div class="col-sm-8">

                        <div class="form-group @if( $errors->has('address1'))  has-error @endif">
                            <label for="address1" class="col-md-4">{{ trans('messages.address') }} </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control p-street-address" id="address1" name="address1"  value="{{ old('address1', (isset($hospital->address1)) ? $hospital->address1 : null) }}" />
                                @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group @if( $errors->has('address2'))  has-error @endif">
                            <label for="address2" class="col-md-4">{{ trans('messages.building_name') }} </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control p-extended-address" id="address2" name="address2"  value="{{ old('address2', (isset($hospital->address2)) ? $hospital->address2 : null) }}" />
                                @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-4">

                        <div class="form-group @if( $errors->has('latitude'))  has-error @endif">
                            <label for="latitude" class="col-md-4">{{ trans('messages.latitude') }} </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="latitude" name="latitude"  value="{{ old('latitude', (isset($hospital->latitude)) ? $hospital->latitude : null) }}" placeholder="0.0000000" />
                                @if ($errors->has('latitude')) <p class="help-block">{{ $errors->first('latitude') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group @if( $errors->has('longitude'))  has-error @endif">
                            <label for="longitude" class="col-md-4">{{ trans('messages.longitude') }} </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="longitude" name="longitude"  value="{{ old('longitude', (isset($hospital->longitude)) ? $hospital->longitude : null) }}" placeholder="0.0000000" />
                                @if ($errors->has('longitude')) <p class="help-block">{{ $errors->first('longitude') }}</p> @endif
                            </div>
                        </div>

                    </div>

                </div>


                <div class="street-view-wrapper" style="margin-left: 50px;">
                    <div class="form-group @if( $errors->has('streetview_url'))  has-error @endif">
                        <label for="streetview_url" class="col-md-4">{{ trans('messages.streetview_url') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="streetview_url" name="streetview_url"  value="{{ old('streetview_url', (isset($hospital->streetview_url)) ? $hospital->streetview_url : null ) }}" placeholder="http://google.com/maps/~" />
                            @if ($errors->has('streetview_url')) <p class="help-block">{{ $errors->first('streetview_url') }}</p> @endif
                        </div>
                    </div>
                </div>

            </div>

        </td>

    </tr>

      <tr>
          <td class="gray-column">
              <label for="tel">{{ trans('messages.contact_information') }} </label>
          </td>
          <td>

              <div class="row">


                  <div class="col-sm-6">


                      <div class="form-group @if( $errors->has('tel'))  has-error @endif">
                          <label for="tel" class="col-md-4">{{ trans('messages.tel') }} </label>
                          <div class="col-md-8">
                              <input type="text" class="form-control" id="tel" name="tel"  value="{{ old('tel', (isset($hospital->tel)) ? $hospital->tel : null) }}" />
                              @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
                          </div>
                      </div>


                  </div>


                  <div class="col-sm-6">

                      <div class="form-group @if( $errors->has('paycall'))  has-error @endif">
                          <label for="paycall" class="col-md-4">{{ trans('messages.paycall') }} </label>
                          <div class="col-md-8">
                              <input type="text" class="form-control" id="paycall" name="paycall"  value="{{ old('paycall', (isset($hospital->paycall)) ? $hospital->paycall : null) }}" />
                              @if ($errors->has('paycall')) <p class="help-block">{{ $errors->first('paycall') }}</p> @endif
                          </div>
                      </div>


                  </div>


              </div>

          </td>
      </tr>
      @for($i= 1; $i<= 5; $i++)
          <tr>
              <td class="gray-column">
                  <label for="tel">最寄り駅 {{ $i }} </label>
              </td>
              <td>

                  <div class="wrapbox" style="padding: 20px;">

                      <div class="row">


                          <div class="col-md-4">

                              <div class="form-group ml-0 mr-0">

                                  <select id="rail{{$i}}" name="rail{{$i}}" class="custom-select form-control">
                                      <option value="">路線を選択</option>
                                      @foreach($rails as $rail)
                                          <option value="{{ $rail->id }}"
                                                  @if ( old('rail' . $i, (isset($hospital->{'rail'. $i})) ? $hospital->{'rail'. $i} : null) == $rail->id)
                                                  selected="selected"
                                                  @endif
                                          >{{ $rail->name }}</option>
                                      @endforeach
                                  </select>

                              </div>

                          </div>

                          <div class="col-md-4">

                              <div class="form-group ml-0 mr-0">

                                  <select id="station{{$i}}" name="station{{$i}}" class="custom-select form-control">
                                      <option value="">駅を選択</option>
                                      @foreach($stations as $station)
                                          <option value="{{ $station->id }}"
                                                  @if ( old('station' . $i, (isset($hospital->{'station'. $i})) ? $hospital->{'station'. $i} : null) == $station->id)
                                                  selected="selected"
                                                  @endif
                                          >{{ $station->name }}</option>
                                      @endforeach
                                  </select>

                              </div>

                          </div>

                          <div class="col-md-4">

                              <div class="form-group ml-0 mr-0">

                                  <input type="text" class="form-control" id="access{{$i}}" name="access{{$i}}" value="{{ old("access{$i}", (isset($hospital->{'access'. $i})) ? $hospital->{'access'. $i} : null) }}" />

                              </div>

                          </div>

                      </div>

                  </div>


              </td>

          </tr>
        @endfor

      <tr>
        
        <td class="gray-column">
          {{ trans('messages.business_hours') }}
        </td>

        <td>
            
            <div class="wrapbox" style="padding: 20px;">

                <h6>hh:mm形式　fromよりも遅い時間</h6>
                <table class="table table-bordered">
                  
                  @for($i= 1; $i<= 4; $i++)
                      <tr class="timebox">

                        <td>
                          診療時間 {{  $i }}
                        </td>
                        
                        <td>

                            @if ( isset($medical_treatment_times[$i-1]) )
                                <input type="hidden" name="medical_treatment_time[{{$i}}][id]" value="{{ $medical_treatment_times[$i-1]->id }}">
                            @endif

                          <div class="col-md-6">
                              <div class="form-group  @if( $errors->has("medical_treatment_time." .$i. ".start")) has-error @endif">
                                  <label for="start-time-{{ $i }}" class="col-md-3">{{ trans('messages.start') }} </label>
                                  <div class="col-md-9">
                                      <input size="5" type="text" class="form-control time-picker" id="start-time-{{ $i }}" name="medical_treatment_time[{{$i}}][start]"
                                             value="{{ old('medical_treatment_time.' . $i . '.start', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->start : null)) }}"
                                      />
                                      @if( $errors->has("medical_treatment_time." .$i. ".start"))
                                        <p class="help-block">{{ $errors->first("medical_treatment_time." .$i. ".start") }}</p>
                                       @endif
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group   @if( $errors->has("medical_treatment_time." .$i. ".end")) has-error @endif">
                                  <label for="end-time-{{$i}}" class="col-md-3">〜</label>
                                  <div class="col-md-9">
                                      <input size="5" type="text" class="form-control time-picker" id="end-time-{{$i}}" name="medical_treatment_time[{{$i}}][end]"
                                             value="{{ old('medical_treatment_time.' . $i . '.end', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->end : null)) }}"
                                      />
                                      @if( $errors->has("medical_treatment_time." .$i. ".end"))
                                        <p class="help-block">{{ $errors->first("medical_treatment_time." .$i. ".end") }}</p>
                                       @endif
                                  </div>
                              </div>
                          </div>

                          <div class="daybox">
                              <label class="checkbox-inline">
                                  <input type="checkbox" name="medical_treatment_time[{{$i}}][mon]"
                                         value="1" @if ( old('medical_treatment_time.' . $i . '.mon', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->mon : null) ) == 1 ) checked @endif>{{ trans('messages.mon') }}
                              </label>

                              <label class="checkbox-inline">
                                  <input type="checkbox" name="medical_treatment_time[{{$i}}][tue]"  value="1" @if ( old('medical_treatment_time.' . $i . '.tue', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->tue : null)) == 1 ) checked @endif> {{ trans('messages.tue') }}
                              </label>

                              <label class="checkbox-inline">
                                  <input type="checkbox" name="medical_treatment_time[{{$i}}][wed]"  value="1" @if ( old('medical_treatment_time.' . $i . '.wed', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->wed : null)) == 1 ) checked @endif> {{ trans('messages.wed') }}
                              </label>

                              <label class="checkbox-inline">
                                  <input type="checkbox" name="medical_treatment_time[{{$i}}][thu]"  value="1" @if ( old('medical_treatment_time.' . $i . '.thu', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->thu : null)) == 1 ) checked @endif> {{ trans('messages.thu') }}
                              </label>

                              <label class="checkbox-inline">
                                  <input type="checkbox" name="medical_treatment_time[{{$i}}][fri]"  value="1" @if ( old('medical_treatment_time.' . $i . '.fri', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->fri : null)) == 1 ) checked @endif> {{ trans('messages.fri') }}
                              </label>

                              <label class="checkbox-inline">
                                  <input type="checkbox" name="medical_treatment_time[{{$i}}][sat]"  value="1" @if ( old('medical_treatment_time.' . $i . '.sat', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->sat : null)) == 1 ) checked @endif> {{ trans('messages.sat') }}
                              </label>

                              <label class="checkbox-inline">
                                  <input type="checkbox" name="medical_treatment_time[{{$i}}][sun]"  value="1" @if ( old('medical_treatment_time.' . $i . '.sun', ( isset($medical_treatment_times[$i-1]) ? $medical_treatment_times[$i-1]->sun : null)) == 1 ) checked @endif> {{ trans('messages.sun') }}
                              </label>
                          </div>                          

                        </td>

                      </tr>
                      @endfor

                </table>

                <div class="form-group @if( $errors->has('consultation_note'))  has-error @endif">
                    <label for="consultation_note" class="col-md-2">{{ trans('messages.consultation_note') }} </label>
                    <div class="col-md-10">
                        <textarea name="consultation_note" id="consultation_note"   rows="5" class="form-control">{{ old('consultation_note',(isset($hospital->consultation_note)) ? $hospital->consultation_note : null) }}</textarea>
                        @if ($errors->has('consultation_note')) <p class="help-block">{{ $errors->first('consultation_note') }}</p> @endif
                    </div>
                </div>              
                
                <div class="form-group @if( $errors->has('memo'))  has-error @endif">
                    <label for="memo" class="col-md-2">備考</label>
                    <div class="col-md-10">
                        <textarea name="memo" id="memo"   rows="5" class="form-control">{{ old('memo', (isset($hospital->memo)) ? $hospital->memo : null) }}</textarea>
                        @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
                    </div>
                </div>              

            </div>  

        </td>

      </tr>

      <tr>
          <td class="gray-column">
              <label for="medical_examination_system_id">{{ trans('messages.examination_system_name') }} </label>
          </td>
          <td>
              <div class="form-group ml-2 mr-2  @if( $errors->has('medical_examination_system_id'))  has-error @endif">
                <select name="medical_examination_system_id" id="medical_examination_system_id" class="form-control">
                    <option value=""></option>
                    @foreach($medical_examination_systems as $medical_examination_system)
                    <option value="{{ $medical_examination_system->id }}"
                            @if ($medical_examination_system->id == old('medical_examination_system_id', (isset($hospital->medical_examination_system_id)) ? $hospital->medical_examination_system_id : null))
                                selected="selected"
                            @endif
                    > {{ $medical_examination_system->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('medical_examination_system_id')) <p class="help-block">{{ $errors->first('medical_examination_system_id') }}</p> @endif
              </div>
          </td>
      </tr>

    </div>
</table>

<div class="col-md-12 pb-5">
    <button type="submit" class="btn btn-primary pull-right">保存</button>
</div>
<br/>

@push('js')
    <script src="{{ asset('js/yubinbango.js') }}" charset="UTF-8"></script>
    <script>
        (function ($) {

            /* ---------------------------------------------------
             combine postcode before submit
            -----------------------------------------------------*/
            $('#postcode-search').click(function(event){
                event.preventDefault();
                event.stopPropagation();
                $('#postcode').val($('#postcode1').val());
                //to trigger native keyup event
                $('#postcode')[0].dispatchEvent(new KeyboardEvent('keyup', {'key': ''}));

                //select distict code id
                setTimeout(function(){
                    distict_code_selector($('#prefecture').val());
                }, 500);
            });

            function distict_code_selector($option_id) {
                $('#district_code_id option').hide();
                let district_code_option = $('select#district_code_id option[data-prefecture_id="' + $option_id +'"]');
                district_code_option.first().attr('selected', true);
                district_code_option.show();
            }

            /* ---------------------------------------------------
            Select district_code_id by prefecture id
            -----------------------------------------------------*/
            $(document).on('change', '#prefecture', function(){
                distict_code_selector($(this).val());
            });

        })(jQuery);
    </script>
@endpush
@push('css')
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
