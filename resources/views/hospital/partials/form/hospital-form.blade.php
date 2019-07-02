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
    <label for="karada_dog_id" class="col-md-4">{{ trans('messages.name') }}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="name" name="name"  value="{{ old('name', (isset($hospital->name) ) ?: null) }}" />
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('kana'))  has-error @endif">
    <label for="karada_dog_id" class="col-md-4">{{ trans('messages.kana') }}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="kana" name="kana"  value="{{ old('kana', (isset($hospital->kana) ) ?: null) }}" />
        @if ($errors->has('kana')) <p class="help-block">{{ $errors->first('kana') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('postcode'))  has-error @endif">
    <label for="karada_dog_id" class="col-md-4">{{ trans('messages.postcode') }}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="postcode" name="kana"  value="{{ old('postcode', (isset($hospital->postcode) ) ?: null) }}" />
        @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
    </div>
</div>


<div class="form-group @if( $errors->has('prefecture'))  has-error @endif">
    <label for="karada_dog_id" class="col-md-4">{{ trans('messages.postcode') }}</label>
    <div class="col-md-8">
        <select name="prefecture" id="prefecture" class="form-control">
            @foreach($prefectures as $prefecture)
            <option value="{{ $prefecture->name }}"
                    @if ( old('prefecture') && ($prefecture->name === old('$prefecture')))
                        selected="selected"
                    @endif
            > {{ $prefecture->name }}</option>
            @endforeach
        </select>
        @if ($errors->has('prefecture')) <p class="help-block">{{ $errors->first('prefecture') }}</p> @endif
    </div>
</div>





