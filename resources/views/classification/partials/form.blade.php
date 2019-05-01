@php
  use \App\Enums\Status;
  use \App\Enums\Authority;
  use \App\Enums\Permission;

  if ($type == 'major')
    $type_label = '大分数';
  else if ($type == 'middle')
    $type_label = '中分数';
  else
    $type_label = '小分数';

@endphp
<div class="box-body">
  {!! csrf_field() !!}
  <input type="hidden" name="classification" value="{{ $type }}">
  @if ($type == 'major')
    <div class="form-group @if ($errors->has('classification_type_id')) has-error @endif" >
      <label for="classification_type_id">分数種別 </label>
      @if(!isset($classification))
        <select name="classification_type_id" id="classification_type_id" class="form-control">
          <option value="">なし</option>
          @foreach ($classification_types as $c_type)
            <option {{ old('classification_type_id') == $c_type->id ? 'selected' : '' }}
                value="{{ $c_type->id }}"> {{ $c_type->name }}</option>
          @endforeach
        </select>
        @if ($errors->has('classification_type_id')) <p class="help-block">{{ $errors->first('classification_type_id') }}</p> @endif
      @else
        <span class="form-control">{{ $classification->classification_type->name }}</span>
      @endif
    </div>
  @endif

  <div class="form-group">
    <label>{{ $type_label }}ID</label>
    <span class="form-control">
      {{ isset($classification) ? $classification->id : '新規' }}
    </span>
  </div>

  @if ($type != 'major')
    <div class="form-group @if ($errors->has('major_classification_id')) has-error @endif" >
      <label for="major_classification_id">大分数</label>
      @if(!isset($classification))
        <select name="major_classification_id" id="major_classification_id" class="form-control">
          <option value="">なし</option>
          @foreach ($c_majors as $c_major)
            <option {{ old('major_classification_id') == $c_major->id ? 'selected' : '' }}
                value="{{ $c_major->id }}"> {{ $c_major->name }}</option>
          @endforeach
        </select>
        @if ($errors->has('major_classification_id')) <p class="help-block">{{ $errors->first('major_classification_id') }}</p> @endif
      @else
        <span class="form-control">{{ $classification->major_classification->name }}</span>
      @endif
    </div>
  @endif

  @if ($type == 'minor')
    <div class="form-group @if ($errors->has('middle_classification_id')) has-error @endif" >
      <label for="middle_classification_id">中分数名</label>
      @if(!isset($classification))
        <select name="middle_classification_id" id="middle_classification_id" class="form-control">
          <option value="">なし</option>
          @foreach ($c_middles as $c_middle)
            <option {{ old('middle_classification_id') == $c_middle->id ? 'selected' : '' }}
                data-major-id="{{$c_middle->major_classification_id}}" value="{{ $c_middle->id }}"> {{ $c_middle->name }}</option>
          @endforeach
        </select>
        @if ($errors->has('middle_classification_id')) <p class="help-block">{{ $errors->first('middle_classification_id') }}</p> @endif
      @else
        <span class="form-control">{{ $classification->middle_classification->name }}</span>
      @endif
    </div>
  @endif


  <div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">{{ $type_label }}名</label>
    <input type="text" class="form-control" name="name" id="name" placeholder="{{ $type_label }}名"
           value="{{ old('name', (isset($classification) ? $classification->name : null)) }}"/>
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
  </div>

@if ($type != 'major')
    <div class="form-group @if ($errors->has('is_icon')) has-error @endif">
      <label for="is_icon">アイコン表示区分</label>
      <div class="radio">
        <label>
          <input type="radio" name="is_icon"
                 {{ old('is_icon', (isset($classification) ? $classification->is_icon : null)) == '1' ? 'checked' : '' }}
                 value="1">
          表示する
        </label>
        <label class="ml-3">
          <input type="radio" name="is_icon"
                 {{ old('is_icon', (isset($classification) ? $classification->is_icon : null)) == '0' ? 'checked' : '' }}
                 value="0">
          表示しない
        </label>
      </div>
      @if ($errors->has('is_icon')) <p class="help-block">{{ $errors->first('is_icon') }}</p> @endif
    </div>



    <div class="form-group @if ($errors->has('icon_name')) has-error @endif">
      <label for="icon_name">アイコン表示分</label>
      <input type="text" class="form-control" name="icon_name" id="icon_name" placeholder="アイコン表示分"
             value="{{ old('icon_name', (isset($classification) ? $classification->icon_name : null)) }}"/>
      @if ($errors->has('icon_name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
  @endif

  @if ($type == 'minor')
    <div class="form-group @if ($errors->has('is_fregist')) has-error @endif">
      <label for="is_fregist">登録区分</label>
      <div class="radio">
        <label>
          <input type="radio" name="is_fregist"
                 {{ old('is_fregist', (isset($classification) ? $classification->is_fregist : '0')) === '0' ? 'checked' : '' }}
                 value="0">
          テキスト
        </label>
        <label class="ml-3">
          <input type="radio" name="is_fregist"
                 {{ old('is_fregist', (isset($classification) ? $classification->is_fregist : null)) === '1' ? 'checked' : '' }}
                 value="1">
          チェックボックス
        </label>
      </div>
      @if ($errors->has('is_fregist')) <p class="help-block">{{ $errors->first('is_fregist') }}</p> @endif
    </div>



    <div class="form-group @if ($errors->has('max_length')) has-error @endif">
      <label for="max_length">テキスト長</label>
      <input type="text" class="form-control" name="max_length" id="max_length" placeholder="テキスト長"
             value="{{ old('max_length', (isset($classification) ? $classification->max_length : null)) }}"/>
      @if ($errors->has('max_length')) <p class="help-block">{{ $errors->first('max_length') }}</p> @endif
    </div>
  @endif

  <div class="form-group @if ($errors->has('status')) has-error @endif">
    <label for="status">状態</label>
    <div class="radio">
      <label>
        <input type="radio" name="status"
               {{ old('status', (isset($staff) ? $staff->status->value : Status::Valid) ) == Status::Valid ? 'checked' : '' }}
               value="{{ Status::Valid }}">
        {{ Status::Valid()->description }}
      </label>
      <label class="ml-3">
        <input type="radio" name="status"
               {{ old('status', (isset($staff) ? $staff->status->value : null)) == Status::Deleted ? 'checked' : '' }}
               value="{{ Status::Deleted }}">
        {{ Status::Deleted()->description }}
      </label>
    </div>
    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
  </div>

  <div class="box-footer">
    <a href="{{ url()->previous() }}" class="btn btn-default">バック</a>
    <button type="submit" class="btn btn-primary">つくる</button>
  </div>

</div>

@section('script')
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // icon flag change
          -----------------------------------------------------*/
          (function () {
              const isIconChange = function() {
                  if ($('input[name="is_icon"]:checked').val() == '1') {
                      $('#icon_name').removeAttr('disabled');
                  } else {
                      $('#icon_name').attr('disabled', 'disabled');
                  }
              }
              $('input[name="is_icon"]').change(isIconChange);
              isIconChange();
          })();

          /* ---------------------------------------------------
          // fregist change
          -----------------------------------------------------*/
          (function () {
              const isFregistChange = function() {
                  if ($('input[name="is_fregist"]:checked').val() == '0') {
                      $('#max_length').removeAttr('disabled');
                  } else {
                      $('#max_length').attr('disabled', 'disabled');
                  }
              }
              $('input[name="is_fregist"]').change(isFregistChange);
              isFregistChange();
          })();

          /* ---------------------------------------------------
          // classification change
          -----------------------------------------------------*/
          (function () {
              const majorEle = $('#major_classification_id');
              const onMajorChange = function() {
                  const selected = majorEle.val();
                  if (selected == ''){
                      $('#middle_classification_id option').show();
                  } else {
                      $('#middle_classification_id option').each(function(i, option) {
                          option = $(option);
                          if (option.val() == '' || selected == option.data('major-id')) {
                              option.show();
                          } else {
                              option.hide();
                              if (option.is(':selected')){
                                  $('#middle_classification_id').val('');
                              }
                          }
                      });
                  }
              };
              majorEle.change(onMajorChange);
              onMajorChange();
          })();

      })(jQuery);
  </script>
@stop