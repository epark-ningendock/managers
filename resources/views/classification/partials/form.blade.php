@php
  use \App\Enums\Status;
  use \App\Enums\Authority;
  use \App\Enums\Permission;

  if ($type == 'major')
    $type_label = '大分類';
  else if ($type == 'middle')
    $type_label = '中分類';
  else
    $type_label = '小分類';

@endphp
@include('layouts.partials.error_message_show')
<div class="form-entry">
    <div class="box-body">
      {!! csrf_field() !!}
      <h2>検査コース分類編集</h2>
      <input type="hidden" name="classification" value="{{ $type }}">
      @if ($type == 'major')
        <div class="form-group @if ($errors->has('classification_type_id')) has-error @endif" >
          <label for="classification_type_id">分類種別</label>
          @if(!isset($classification))
            <select name="classification_type_id" id="classification_type_id" class="form-control">
              @foreach ($classification_types as $c_type)
                <option {{ old('classification_type_id') == $c_type->id ? 'selected' : '' }}
                    value="{{ $c_type->id }}"> {{ $c_type->name }}</option>
              @endforeach
            </select>
            @if ($errors->has('classification_type_id')) <p class="help-block">{{ $errors->first('classification_type_id') }}</p> @endif
          @else
            <span class="form-control" disabled>{{ $classification->classification_type->name }}</span>
          @endif
        </div>
      @endif

      <div class="form-group">
        <label>{{ $type_label }}ID</label>
        <span disabled class="form-control">
          {{ isset($classification) ? $classification->id : '新規' }}
        </span>
      </div>

      @if ($type != 'major')
        <div class="form-group @if ($errors->has('major_classification_id')) has-error @endif" >
          <label for="major_classification_id">大分類</label>
          @if(!isset($classification))
            <select name="major_classification_id" id="major_classification_id" class="form-control">
              @foreach ($c_majors as $c_major)
                <option {{ old('major_classification_id') == $c_major->id ? 'selected' : '' }}
                    value="{{ $c_major->id }}"> {{ $c_major->name }}</option>
              @endforeach
            </select>
            @if ($errors->has('major_classification_id')) <p class="help-block has-error">{{ $errors->first('major_classification_id') }}</p> @endif
          @else
            <span class="form-control" disabled>{{ $classification->major_classification->name }}</span>
          @endif
        </div>
      @endif

      @if ($type == 'minor')
        <div class="form-group @if ($errors->has('middle_classification_id')) has-error @endif" >
          <label for="middle_classification_id">中分類名</label>
          @if(!isset($classification))
            <select name="middle_classification_id" id="middle_classification_id" class="form-control">
              @foreach ($c_middles as $c_middle)
                <option {{ old('middle_classification_id') == $c_middle->id ? 'selected' : '' }}
                    data-major-id="{{$c_middle->major_classification_id}}" value="{{ $c_middle->id }}"> {{ $c_middle->name }}</option>
              @endforeach
            </select>
            @if ($errors->has('middle_classification_id')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('middle_classification_id') }}</p> @endif
          @else
            <span class="form-control" disabled>{{ $classification->middle_classification->name }}</span>
          @endif
        </div>
      @endif

      <div class="form-group @if ($errors->has('name')) has-error @endif">
        <label for="name">{{ $type_label }}名<span class="form_required">必須</span></label>
        <input type="text" class="form-control" name="name" id="name" placeholder="{{ $type_label }}名"
               value="{{ old('name', (isset($classification) ? $classification->name : null)) }}"/>
        @if ($errors->has('name')) <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('name') }}</p> @endif
      </div>

    @if ($type != 'major')
        <div class="form-group py-sm-2">
            <input type="hidden" name="updated_at" value="{{ isset($classification) ? $classification->updated_at : null }}">
            <label for="is_icon">アイコン表示区分</label>
            <group class="inline-radio two-option">
                <div>
                    <input type="radio" name="is_icon" {{ old('is_icon', (isset($classification) ? $classification->is_icon : null) ) == '1' ? 'checked' : 'checked' }}
                    value="1"
                    ><label>表示</label></div>
                <div>
                    <input type="radio" name="is_icon" {{ old('is_icon', (isset($classification) ? $classification->is_icon : null)) == '0' ? 'checked' : '' }}
                    value="0"><label>非表示</label></div>
            </group>
            @if ($errors->has('is_icon')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('is_icon') }}</p> @endif
        </div>


        <div class="form-group @if ($errors->has('icon_name')) has-error @endif">
          <label for="icon_name">アイコン表示分</label>
          <input type="text" class="form-control" name="icon_name" id="icon_name" placeholder="アイコン表示分"
                 value="{{ old('icon_name', (isset($classification) ? $classification->icon_name : null)) }}"/>
          @if ($errors->has('icon_name')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('icon_name') }}</p> @endif
        </div>
      @endif

      @if ($type == 'minor')
        <div class="form-group @if ($errors->has('is_fregist')) has-error @endif">
            <fieldset class="form-group mt-3">
                <legend class="mt-3">登録区分</legend>
                <div class="radio">
                    <input id="is_fregist_text" type="radio" name="is_fregist"
                           {{ old('is_fregist', (isset($classification) ? $classification->is_fregist : '0')) === '0' ? 'checked' : '' }}
                           value="0">
                    <label for="is_fregist_text" class="radio-label">テキスト</label>
                </div>
                <div class="radio">
                    <input type="radio" id="is_fregist_radio" name="is_fregist"
                               {{ old('is_fregist', (isset($classification) ? $classification->is_fregist : '1')) === '1' ? 'checked' : '' }}
                               value="1">
                    <label for="is_fregist_radio" class="radio-label">チェックボックス</label>
                </div>
            </fieldset>
            @if ($errors->has('is_fregist')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('is_fregist') }}</p> @endif
        </div>



        <div class="form-group @if ($errors->has('max_length')) has-error @endif">
          <label for="max_length">テキスト長</label>
          <input type="text" class="form-control" name="max_length" id="max_length" placeholder="テキスト長"
                 value="{{ old('max_length', (isset($classification) ? $classification->max_length : null)) }}"/>
          @if ($errors->has('max_length')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('max_length') }}</p> @endif
        </div>
      @endif

        <div class="form-group py-sm-2">
            <label for="is_icon">状態</label>
            <group class="inline-radio two-option">
                <div>
                    <input type="radio" name="status" {{ old('is_icon', (isset($classification) ? $classification->status : null) ) == '1' ? 'checked' : 'checked' }}
                    value="1"
                    ><label>有効</label></div>
                <div>
                    <input type="radio" name="status" {{ old('is_icon', (isset($classification) ? $classification->status : null)) == 'X' ? 'checked' : '' }}
                    value="X"><label>削除</label></div>
            </group>
            @if ($errors->has('is_icon')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('status') }}</p> @endif
        </div>

      <div class="box-footer">
        <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">登録</button>
      </div>

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
                      const middleSelectedFlg = true;
                      $('#middle_classification_id option').each(function(i, option) {
                          option = $(option);
                          if (option.val() == '' || selected == option.data('major-id')) {
                              option.show();
                              if (middleSelectedFlg) {
                                option.prop('selected', true);
                                middleSelectedFlg = false;
                              }
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