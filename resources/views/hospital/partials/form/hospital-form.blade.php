<div class="form-group @if( $errors->has('status'))  has-error @endif">

    <label for="karada_dog_id" class="col-md-4">状態</label>

    <div class="col-md-8">

        <div class="radio">
            <label>
                <input type="radio" name="status" id="public_status" value="{{ \App\Enums\Status::Public }}">
                {{ \App\Enums\Status::getDescription('1') }}
            </label>
        </div>

        <div class="radio">
            <label>
                <input type="radio" name="status" id="private_status" value="{{ \App\Enums\Status::Private }}">
                {{ \App\Enums\Status::getDescription('0') }}
            </label>
        </div>

        <div class="radio">
            <label>
                <input type="radio" name="status" id="deleted_status" value="{{ \App\Enums\Status::Deleted }}">
                {{ \App\Enums\Status::getDescription('X') }}
            </label>
        </div>


        @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif

    </div>
</div>



<div class="form-group @if( $errors->has('name'))  has-error @endif">
    <label for="name" class="col-md-4">{{ trans('messages.name') }}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="name" name="name"  value="{{ old('name', (isset($hospital->name) ) ?: null) }}" />
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('kana'))  has-error @endif">
    <label for="kana" class="col-md-4">{{ trans('messages.kana') }}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="kana" name="kana"  value="{{ old('kana', (isset($hospital->kana) ) ?: null) }}" />
        @if ($errors->has('kana')) <p class="help-block">{{ $errors->first('kana') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('postcode'))  has-error @endif">
    <label for="postcode" class="col-md-4">{{ trans('messages.postcode') }}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="postcode" name="postcode"  value="{{ old('postcode', (isset($hospital->postcode) ) ?: null) }}" />
        @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('prefecture'))  has-error @endif">
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


<div class="form-group @if( $errors->has('district_code'))  has-error @endif">
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


<div class="form-group @if( $errors->has('address1'))  has-error @endif">
    <label for="address1" class="col-md-4">{{ trans('messages.address') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="address1" name="address1"  value="{{ old('address1', (isset($hospital->address1) ) ?: null) }}" />
        @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('address2'))  has-error @endif">
    <label for="address2" class="col-md-4">{{ trans('messages.building_name') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="address2" name="address2"  value="{{ old('address2', (isset($hospital->address2) ) ?: null) }}" />
        @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('latitude'))  has-error @endif">
    <label for="latitude" class="col-md-4">{{ trans('messages.latitude') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="latitude" name="latitude"  value="{{ old('latitude', (isset($hospital->latitude) ) ?: null) }}" />
        @if ($errors->has('latitude')) <p class="help-block">{{ $errors->first('latitude') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('longitude'))  has-error @endif">
    <label for="longitude" class="col-md-4">{{ trans('messages.longitude') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="longitude" name="longitude"  value="{{ old('longitude', (isset($hospital->longitude) ) ?: null) }}" />
        @if ($errors->has('longitude')) <p class="help-block">{{ $errors->first('longitude') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('streetview_url'))  has-error @endif">
    <label for="streetview_url" class="col-md-4">{{ trans('messages.streetview_url') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="streetview_url" name="streetview_url"  value="{{ old('streetview_url', (isset($hospital->streetview_url) ) ?: null) }}" />
        @if ($errors->has('streetview_url')) <p class="help-block">{{ $errors->first('streetview_url') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('tel'))  has-error @endif">
    <label for="tel" class="col-md-4">{{ trans('messages.tel') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="tel" name="tel"  value="{{ old('tel', (isset($hospital->tel) ) ?: null) }}" />
        @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('paycall'))  has-error @endif">
    <label for="paycall" class="col-md-4">{{ trans('messages.paycall') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="paycall" name="paycall"  value="{{ old('paycall', (isset($hospital->paycall) ) ?: null) }}" />
        @if ($errors->has('paycall')) <p class="help-block">{{ $errors->first('paycall') }}</p> @endif
    </div>
</div>



<div class="form-group @if( $errors->has('rail'))  has-error @endif">
    <label for="rail" class="col-md-4">{{ trans('messages.rail') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="rail" name="rail"  value="{{ old('rail', (isset($hospital->rail) ) ?: null) }}" />
        @if ($errors->has('rail')) <p class="help-block">{{ $errors->first('rail') }}</p> @endif
    </div>
</div>



<div class="form-group @if( $errors->has('station'))  has-error @endif">
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



<div class="form-group @if( $errors->has('access'))  has-error @endif">
    <label for="access" class="col-md-4">{{ trans('messages.access') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="access" name="access"  value="{{ old('access', (isset($hospital->access) ) ?: null) }}" />
        @if ($errors->has('access')) <p class="help-block">{{ $errors->first('access') }}</p> @endif
    </div>
</div>



@for($i= 1; $i<= 4; $i++)

    <div class="day-row" style="background: #f3f3f3;padding: 10px;margin-bottom: 3px;">

        <div class="row">

            <div class="col-md-4">
                診療時間 {{ $i }}
            </div>

            <div class="col-md-8">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="start{{ $i }}" class="col-md-2">{{ trans('messages.start') }} </label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="start{{ $i }}" name="medical_treatment_time[{{$i}}][start]"  value="{{ old('medical_treatment_time[' . $i . '][start]') }}" />
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="end{{ $i }}" class="col-md-2">{{ trans('messages.end') }} </label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="end{{ $i }}" name="medical_treatment_time[{{$i}}][end]"  value="{{ old('medical_treatment_time[' . $i . '][end]') }}" />
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>

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

        </div>

    </div>


@endfor



<div class="form-group @if( $errors->has('consultation_note'))  has-error @endif">
    <label for="consultation_note" class="col-md-4">{{ trans('messages.consultation_note') }} </label>
    <div class="col-md-8">
        <textarea name="consultation_note" id="consultation_note"   rows="5" class="form-control"></textarea>
        @if ($errors->has('consultation_note')) <p class="help-block">{{ $errors->first('consultation_note') }}</p> @endif
    </div>
</div>



<div class="form-group @if( $errors->has('memo'))  has-error @endif">
    <label for="memo" class="col-md-4">{{ trans('messages.memo') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="memo" name="memo"  value="{{ old('memo', (isset($hospital->memo) ) ?: null) }}" />
        @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('medical_examination_system_id'))  has-error @endif">
    <label for="medical_examination_system_id" class="col-md-4">{{ trans('messages.medical_examination_system_id') }} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="medical_examination_system_id" name="medical_examination_system_id"  value="{{ old('medical_examination_system_id', (isset($hospital->medical_examination_system_id) ) ?: null) }}" />
        @if ($errors->has('medical_examination_system_id')) <p class="help-block">{{ $errors->first('medical_examination_system_id') }}</p> @endif
    </div>
</div>

<div class="col-md-12 pb-5">
    <button type="submit" class="btn btn-success pull-right">登録</button>
</div>
<br/>