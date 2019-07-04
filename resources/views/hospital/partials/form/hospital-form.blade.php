<table class="table table-bordered mb-5 mt-5">
      <tr>
          <td class="gray-column">
              <label for="karada_dog_id" class="col-md-4">状態</label>
          </td>
          <td>
              <div class="col-md-8">
                  <div class="form-group @if( $errors->has('status'))  has-error @endif">
                      <div class="radio">
                          <label>
                              <input type="radio" name="status" id="private_status" value="{{ \App\Enums\Status::Private }}">
                              {{ \App\Enums\Status::getDescription('0') }}
                          </label>

                          <label class="ml-3">
                              <input type="radio" name="status" id="public_status" value="{{ \App\Enums\Status::Public }}">
                              {{ \App\Enums\Status::getDescription('1') }}
                          </label>

                          <label class="ml-3">
                              <input type="radio" name="status" id="deleted_status" value="{{ \App\Enums\Status::Deleted }}">
                              {{ \App\Enums\Status::getDescription('X') }}
                          </label>
                      </div>
                      @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                  </div>
              </div>
          </td>
      </tr>

      <tr>
          <td class="gray-column">
              <label for="name" class="col-md-4">{{ trans('messages.names.hospital') }}</label>
          </td>
          <td>
            <div class="col-md-8">
                <div class="row">
                    <div>
                        <div class="form-group">
                            <label for="name" class="col-md-2">{{ trans('messages.name') }}</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="name" name="name"  value="{{ old('name', (isset($hospital->name) ) ?: null) }}" />
                                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div>
                        <div class="form-group">
                            <label for="name" class="col-md-2">{{ trans('messages.kana') }}</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="kana" name="kana"  value="{{ old('kana', (isset($hospital->kana) ) ?: null) }}" />
                                @if ($errors->has('kana')) <p class="help-block">{{ $errors->first('kana') }}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </td>
      </tr>

      <tr>
          <td class="gray-column">
              <label for="name" class="col-md-4">{{ trans('messages.location') }}</label>
          </td>
          <td>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group">
                        <label for="postcode" class="col-md-4">{{ trans('messages.postcode') }}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="postcode" name="postcode"  value="{{ old('postcode', (isset($hospital->postcode) ) ?: null) }}" />
                            @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="prefecture" class="col-md-4">{{ trans('messages.prefectures') }}</label>
                        <div class="col-md-8">
                            <select name="prefecture" id="prefecture" class="form-control">
                                @foreach($prefectures as $prefecture)
                                <option value="{{ $prefecture->name }}"
                                        @if ( old('prefecture') && ($prefecture->name === old('prefecture')))
                                            selected="selected"
                                        @endif
                                > {{ $prefecture->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('prefecture')) <p class="help-block">{{ $errors->first('prefecture') }}</p> @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="district_code" class="col-md-4">{{ trans('messages.district_code') }}</label>
                        <div class="col-md-8">
                            <select name="district_code" id="prefecture" class="form-control">
                                @foreach($district_codes as $district_code)
                                    <option value="{{ $district_code->id }}"
                                            @if ( old('district_code') && ($district_code->name === old('district_code')))
                                            selected="selected"
                                            @endif
                                    > {{ $district_code->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('district_code')) <p class="help-block">{{ $errors->first('district_code') }}</p> @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address1" class="col-md-4">{{ trans('messages.address') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="address1" name="address1"  value="{{ old('address1', (isset($hospital->address1) ) ?: null) }}" />
                            @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address2" class="col-md-4">{{ trans('messages.building_name') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="address2" name="address2"  value="{{ old('address2', (isset($hospital->address2) ) ?: null) }}" />
                            @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="latitude" class="col-md-4">{{ trans('messages.latitude') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="latitude" name="latitude"  value="{{ old('latitude', (isset($hospital->latitude) ) ?: null) }}" />
                            @if ($errors->has('latitude')) <p class="help-block">{{ $errors->first('latitude') }}</p> @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="longitude" class="col-md-4">{{ trans('messages.longitude') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="longitude" name="longitude"  value="{{ old('longitude', (isset($hospital->longitude) ) ?: null) }}" />
                            @if ($errors->has('longitude')) <p class="help-block">{{ $errors->first('longitude') }}</p> @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="streetview_url" class="col-md-4">{{ trans('messages.streetview_url') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="streetview_url" name="streetview_url"  value="{{ old('streetview_url', (isset($hospital->streetview_url) ) ?: null) }}" />
                            @if ($errors->has('streetview_url')) <p class="help-block">{{ $errors->first('streetview_url') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
          </td>
      </tr>

      <tr>
          <td class="gray-column">
              <label for="tel" class="col-md-4">{{ trans('messages.contact_information') }} </label>
          </td>
          <td>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group">
                        <label for="tel" class="col-md-4">{{ trans('messages.tel') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="tel" name="tel"  value="{{ old('tel', (isset($hospital->tel) ) ?: null) }}" />
                            @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="paycall" class="col-md-4">{{ trans('messages.paycall') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="paycall" name="paycall"  value="{{ old('paycall', (isset($hospital->paycall) ) ?: null) }}" />
                            @if ($errors->has('paycall')) <p class="help-block">{{ $errors->first('paycall') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
          </td>
      </tr>
      @for($i= 1; $i<= 3; $i++)
          <tr>
              <td class="gray-column">
                  <label for="tel" class="col-md-4">最寄り駅 {{ $i }} </label>
              </td>
              <td>
                <div class="col-md-8">
                    <div class="row">
                        <div class="form-group">
                            <label for="rail" class="col-md-4">{{ trans('messages.rail') }} </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="rail" name="rail"  value="{{ old('rail', (isset($hospital->rail) ) ?: null) }}" />
                                @if ($errors->has('rail')) <p class="help-block">{{ $errors->first('rail') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rail" class="col-md-4">{{ trans('messages.station') }} </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="station" name="station"  value="{{ old('station', (isset($hospital->station) ) ?: null) }}" />
                                @if ($errors->has('station')) <p class="help-block">{{ $errors->first('station') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group @if( $errors->has('access'))  has-error @endif">
                            <label for="access" class="col-md-4">{{ trans('messages.access') }} </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="access" name="access"  value="{{ old('access', (isset($hospital->access) ) ?: null) }}" />
                                @if ($errors->has('access')) <p class="help-block">{{ $errors->first('access') }}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>
              </td>
              @endfor
          </tr>


          <tr>
              <td class="gray-column" rowspan="5">
                  <label for="tel" class="col-md-4">{{ trans('messages.business_hours') }}</label>
              </td>
              <td>
                <div class="col-md-8">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="tel">診療時間1</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start-time-1" class="col-md-3">{{ trans('messages.start') }} </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="start-time-1" name="medical_treatment_time[{{$i}}][start]"  value="{{ old('medical_treatment_time[' . $i . '][start]') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end-time-1" class="col-md-3">〜</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="end-time-1" name="medical_treatment_time[{{$i}}][end]"  value="{{ old('medical_treatment_time[' . $i . '][end]') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="daybox">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][mon]"  value="1">{{ trans('messages.mon') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][tue]"  value="1"> {{ trans('messages.tue') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][wed]"  value="1"> {{ trans('messages.wed') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][thu]"  value="1"> {{ trans('messages.thu') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][fri]"  value="1"> {{ trans('messages.fri') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][sat]"  value="1"> {{ trans('messages.sat') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][sun]"  value="1"> {{ trans('messages.sun') }}
                            </label>
                        </div>
                    </div>
                </div>
              </td>
          </tr>

          <tr>
              <td>
                <div class="col-md-8">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="tel">診療時間2</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start-time-2" class="col-md-3">{{ trans('messages.start') }} </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="start-time-2" name="medical_treatment_time[{{$i}}][start]"  value="{{ old('medical_treatment_time[' . $i . '][start]') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end-time-2" class="col-md-3">〜</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="end-time-2" name="medical_treatment_time[{{$i}}][end]"  value="{{ old('medical_treatment_time[' . $i . '][end]') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="daybox">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][mon]"  value="1">{{ trans('messages.mon') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][tue]"  value="1"> {{ trans('messages.tue') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][wed]"  value="1"> {{ trans('messages.wed') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][thu]"  value="1"> {{ trans('messages.thu') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][fri]"  value="1"> {{ trans('messages.fri') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][sat]"  value="1"> {{ trans('messages.sat') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][sun]"  value="1"> {{ trans('messages.sun') }}
                            </label>
                        </div>
                    </div>
                </div>
              </td>
          </tr>

          <tr>
              <td>
                <div class="col-md-8">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="tel">診療時間3</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start-time-3" class="col-md-3">{{ trans('messages.start') }} </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="start-time-3" name="medical_treatment_time[{{$i}}][start]"  value="{{ old('medical_treatment_time[' . $i . '][start]') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end-time-3" class="col-md-3">〜</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="end-time-3" name="medical_treatment_time[{{$i}}][end]"  value="{{ old('medical_treatment_time[' . $i . '][end]') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="daybox">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][mon]"  value="1">{{ trans('messages.mon') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][tue]"  value="1"> {{ trans('messages.tue') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][wed]"  value="1"> {{ trans('messages.wed') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][thu]"  value="1"> {{ trans('messages.thu') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][fri]"  value="1"> {{ trans('messages.fri') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][sat]"  value="1"> {{ trans('messages.sat') }}
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="medical_treatment_time[{{$i}}][sun]"  value="1"> {{ trans('messages.sun') }}
                            </label>
                        </div>
                    </div>
                </div>
              </td>
          </tr>

      <tr>
          <td>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group @if( $errors->has('consultation_note'))  has-error @endif">
                        <label for="consultation_note" class="col-md-4">{{ trans('messages.consultation_note') }} </label>
                        <div class="col-md-8">
                            <textarea name="consultation_note" id="consultation_note"   rows="5" class="form-control"></textarea>
                            @if ($errors->has('consultation_note')) <p class="help-block">{{ $errors->first('consultation_note') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
          </td>
      </tr>

      <tr>
          <td>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group @if( $errors->has('memo'))  has-error @endif">
                      <label for="memo" class="col-md-4">{{ trans('messages.remarks') }} </label>
                      <div class="col-md-8">
                          <input type="text" class="form-control" id="memo" name="memo"  value="{{ old('memo', (isset($hospital->memo) ) ?: null) }}" />
                          @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
                      </div>
                    </div>
                </div>
            </div>
          </td>
      </tr>

      <tr>
          <td class="gray-column">
              <label for="tel" class="col-md-4">{{ trans('messages.examination_system_name') }} </label>
          </td>
          <td>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group @if( $errors->has('medical_examination_system_id'))  has-error @endif">
                        <label for="medical_examination_system_id" class="col-md-4">{{ trans('messages.medical_examination_system_id') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="medical_examination_system_id" name="medical_examination_system_id"  value="{{ old('medical_examination_system_id', (isset($hospital->medical_examination_system_id) ) ?: null) }}" />
                            @if ($errors->has('medical_examination_system_id')) <p class="help-block">{{ $errors->first('medical_examination_system_id') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
          </td>
      </tr>

    </div>
</table>

<div class="col-md-12 pb-5">
    <button type="submit" class="btn btn-success pull-right">登録</button>
</div>
<br/>
