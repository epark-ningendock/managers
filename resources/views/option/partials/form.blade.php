@include('layouts.partials.error_pan')

<div class="form-entry">
    <input type="hidden" name="lock_version" value="{{ $option->lock_version or '' }}" />
    <div class="box-body staff-form">
        <h2>オプション登録</h2>
        <div class="form-group py-sm-1 @if ($errors->has('name')) has-error @endif">
            <label for="option_name">{{ trans('messages.option_name') }}<span class="form_required">必須</span></label>
            <input type="text" class="form-control w24em" id="option_name" name="name"
                   value="{{ old('name', (isset($option) ? $option->name : null)) }}"
                   placeholder="オプション名">
            @if ($errors->has('name')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('name') }}</p> @endif
        </div>
        <div class="form-group py-sm-1 @if ($errors->has('confirm')) has-error @endif">
            <label for="confirm">{{ trans('messages.option_description') }}</label>
            <textarea name="confirm" id="confirm" rows="6"
                      placeholder="{{ trans('messages.option_description') }}"
                      class="form-control w24em">{{ old('confirm', (isset($option) ? $option->confirm : '')) }}</textarea>
            <span class="text-muted d-block text-right" style="text-align: right; display: block; width: 20em">0/128文字</span>
            @if ($errors->has('confirm')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('confirm') }}</p> @endif
        </div>


        <div class="form-group py-sm-1 @if ($errors->has('price')) has-error @endif">
            <label for="confirm">{{ trans('messages.price') }}<span class="form_required">必須</span></label>
            <input type="number" class="form-control w16em inline-block" name="price" id="price"
                   placeholder="{{ trans('messages.price') }}"
                   value="{{ old('price', (isset($option) ? $option->price : '')) }}"/>
            <span class="input-side-text">円（税込）</span>
            @if ($errors->has('price')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('price') }}</p> @endif
        </div>
        <div class="form-group @if ($errors->has('tax_class_id')) has-error @endif" style="display: none">
            <label for="tax_class_id">{{ trans('messages.tax_classification') }}<span class="form_required">必須</span></label>
            @if(isset($tax_classes))
                <select name="tax_class_id" id="tax_class_id" class="w16em form-control">
                    @foreach ($tax_classes as $tax_class)
                        <option
                                @if ( (old('tax_class_id') == $tax_class->id) ||  isset($option->tax_class_id) && ($option->tax_class_id == $tax_class->id) )
                                selected="selected"
                                @endif
                                value="{{ $tax_class->id }}"> {{ $tax_class->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('tax_class_id')) <p
                        class="help-block">{{ $errors->first('tax_class_id') }}</p> @endif
            @else
                <span class="text-danger">税区分</span>
            @endif
        </div>
    </div>

    <div class="box-body staff-form">
        <h2>適用検査コース</h2>
        <div class="form-group py-sm-1" id="option-setting">
            @foreach($courses as $course)
                @if($loop->first)<table class="table no-border table-hover table-striped">@endif
                <tr>
                    <td class="text-left">
                        <input type="checkbox" name="courses[]" id="course_{{ $course->id }}" value="{{ $course->id }}"@if(in_array($course->id, $applied)) checked @endif>
                        <label class="mr-2" for="course_{{ $course->id }}">{{ $course->name }}</label>
                    </td>
                </tr>
                @if($loop->last)</table>@endif
            @endforeach
        </div>
    </div>
</div>
@section('script')
  <script>
      (function ($) {
        $(document).ready(function($){
            const len = $('textarea').val().length;
            if (len > 128) {
                $('textarea').val($('textarea').val().substring(0, 128));
            } else {
                $('textarea').next('span').text(len + '/128文字');
            }
        });
        /* ---------------------------------------------------
        // character count
        -----------------------------------------------------*/
        (function () {
            $('textarea').on('keyup', function() {
                const len = $(this).val().length;
                if (len > 128) {
                    $(this).val($(this).val().substring(0, 128));
                } else {
                    $(this).next('span').text(len + '/128文字');
                }
            });
        })();
      })(jQuery);
  </script>
@stop