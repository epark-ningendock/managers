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
        <label for="status">カレンダー受付可否</label>
        <group class="inline-radio two-option">
            <div>
                <input type="radio" name="is_calendar_display" {{ old('is_calendar_display', (isset($calendar) ? $calendar->is_calendar_display->value : null) ) == CalendarDisplay::Show ? 'checked' : 'checked' }}
                value="{{ CalendarDisplay::Show }}"
                ><label>{{ CalendarDisplay::Show()->description }}</label></div>
            <div>
                <input type="radio" name="is_calendar_display" {{ old('is_calendar_display', (isset($calendar) ? $calendar->is_calendar_display->value : null)) == CalendarDisplay::Hide ? 'checked' : '' }}
                value="{{ CalendarDisplay::Hide }}"><label>{{ CalendarDisplay::Hide()->description }}</label></div>
        </group>
        @if ($errors->has('is_calendar_display')) <p class="help-block has-error">{{ $errors->first('is_calendar_display') }}</p> @endif
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