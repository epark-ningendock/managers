@php
  use \App\Enums\CalendarDisplay;
@endphp

<div class="box-body">
  {!! csrf_field() !!}
  <input type="hidden" name="lock_version" value="{{ $calendar->lock_version or '' }}" />
  <div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">カレンダー名<span class="form_required">必須</span></label>
    <input type="text" class="form-control" name="name" id="name" placeholder="カレンダー名"
           value="{{ old('name', (isset($calendar) ? $calendar->name : null)) }}"/>
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
  </div>

    <div class="form-group py-sm-2 @if ($errors->has('is_calendar_display')) has-error @endif">
        <label for="status">カレンダー表示</label>
        <group class="inline-radio two-option-middle">
            <div>
                <input type="radio" name="is_calendar_display" {{ old('is_calendar_display', (isset($calendar) ? $calendar->is_calendar_display->value : null) ) == CalendarDisplay::SHOW ? 'checked' : 'checked' }}
                value="{{ CalendarDisplay::SHOW }}"
                ><label>{{ CalendarDisplay::SHOW()->description }}</label></div>
            <div>
                <input type="radio" name="is_calendar_display" {{ old('is_calendar_display', (isset($calendar) ? $calendar->is_calendar_display->value : null)) == CalendarDisplay::HIDE ? 'checked' : '' }}
                value="{{ CalendarDisplay::HIDE }}"><label>{{ CalendarDisplay::HIDE()->description }}</label></div>
        </group>
        @if ($errors->has('is_calendar_display')) <p class="help-block has-error">{{ $errors->first('is_calendar_display') }}</p> @endif
    </div>
    <div class="form-group py-sm-2 ">
        <label for="status">カレンダー自動更新</label>
        <group class="inline-radio two-option-large" style="width: 350px;">
            <div>
                <input type="radio" name="auto_update_flg" id="auto_update_flg_true"
                       {{ old('auto_update_flg', (isset($calendar) ? $calendar->auto_update_flg : null) ) == 1 ? 'checked' : '' }}
                       value="1">
                <label for="auto_update_flg_true">自動更新する</label>
            </div>
            <div>
                <input type="radio" name="auto_update_flg" id="auto_update_flg_false"
                       {{ old('in_hospital_email_reception_flg', (isset($calendar) ? $calendar->auto_update_flg : 0) ) == 0 ? 'checked' : '' }}
                       value="0">
                <label id="auto_update_flg_false">自動更新しない</label>
            </div>
        </group>
    </div>
    <div class="form-group @if ($errors->has('auto_update_start_date') or $errors->has('auto_update_end_date')) has-error @endif">
        <label>カレンダー枠自動更新期間</label>
        <div class="form-horizontal display-period">
            <span>開始日</span>
            {{ Form::text('auto_update_start_date', old('auto_update_start_date', (isset($calendar) && isset($calendar->auto_update_start_date) ? $calendar->auto_update_start_date :  null)),
                ['class' => 'd-inline-block w16em form-control', 'id' => 'datetimepicker-disp-start']) }}
            <span>終了日</span>
            {{ Form::text('auto_update_end_date', old('auto_update_end_date', ((isset($calendar) && isset($calendar->auto_update_end_date)) ? $calendar->auto_update_end_date :  null)),
                ['class' => 'd-inline-block w16em form-control', 'id' => 'datetimepicker-disp-end']) }}
        </div>
        @if ($errors->has('auto_update_start_date'))
            <p class="help-block">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                {{ $errors->first('auto_update_start_date') }}
            </p>
        @endif
        @if ($errors->has('auto_update_end_date'))
            <p class="help-block">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                {{ $errors->first('auto_update_end_date') }}
            </p>
        @endif
    </div>
  <div class="form-group">
    <label>検査コース</label>
    <div class="role">
        <div class="course-select clearfix">
          <div class="col-xs-5">
            <label for="">登録済み検査コース</label>
            <select id="registered_courses" multiple class="form-control multi-select">
              @if(isset($calendar))
                @foreach($calendar->courses as $course)
                  <option value="{{ $course->id }}"> {{ $course->name }} </option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="col-xs-2 text-center transfer">
            <button class="btn btn-default" id="register"><span class="glyphicon glyphicon-chevron-left"></span></button>
            <button class="btn btn-default mt-4" id="unregister"><span class="glyphicon glyphicon-chevron-right"></span></button>
          </div>
          <div class="col-xs-5">
            <div class="form-group">
              <label for="">未登録検査コース</label>
              <select id="unregistered_courses" multiple class="form-control multi-select">
                @foreach($unregistered_courses as $u_course)
                  <option value="{{ $u_course->id }}"> {{ $u_course->name }} </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<div class="box-footer">
  <a href="{{ route('calendar.index') }}" class="btn btn-default">戻る</a>
  <button type="submit" class="btn btn-primary">保存</button>
</div>

<style>
  .multi-select {
    min-height: 200px;
    overflow-y: auto;
  }
  .transfer {
    width: 70px;
    margin-top: 80px;
  }
</style>

@section('script')
  <script>
      (function ($) {
          /* ---------------------------------------------------
           // course transfer
          -----------------------------------------------------*/
          (function () {
              $('#register').click(function(event) {
                  event.preventDefault();
                  event.stopPropagation();
                  $('#registered_courses').append($('#unregistered_courses option:selected').prop('selected', false));
              })

              $('#unregister').click(function(event) {
                  event.preventDefault();
                  event.stopPropagation();
                  $('#unregistered_courses').append($('#registered_courses option:selected').prop('selected', false));
              })

          })();

          /* ---------------------------------------------------
           // before form submit
          -----------------------------------------------------*/
          (function () {
              $('form').on('submit', function() {
                  $('#registered_courses option').each(function(i, ele) {
                    $('<input type="hidden" name="registered_course_ids[]" />')
                        .val($(ele).val())
                        .appendTo($('form'))
                  });

                  $('#unregistered_courses option').each(function(i, ele) {
                      $('<input type="hidden" name="unregistered_course_ids[]" />')
                          .val($(ele).val())
                          .appendTo($('form'))
                  });
              })
          })();

      })(jQuery);
  </script>
@stop

@push('js')
    <script src="{{ url('js/handlebars.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.ja.min.js') }}"></script>
    <script src="{{ url('js/bootstrap3-typeahead.min.js') }}"></script>
    <script type="text/javascript">
        (function ($) {
            $('#datetimepicker-disp-start').datepicker({
                language:'ja',
                format: 'yyyy-mm-dd',
            });
            $('#datetimepicker-disp-end').datepicker({
                language:'ja',
                format: 'yyyy-mm-dd',
            });
        })(jQuery);
    </script>
@endpush