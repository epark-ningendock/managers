<div class="form-group @if ($errors->has('web_reception')) has-error @endif">
  <label for="web_reception">WEBの受付</label>
  <div class="radio">
    <label>
      <input type="radio" name="web_reception"
             {{ old('web_reception', (isset($course) ? $course->web_reception->value : null) ) == WebReception::Accept ? 'checked' : '' }}
             value="{{ WebReception::Accept }}">
      受け付ける
    </label>
    <label class="ml-3">
      <input type="radio" name="web_reception"
             {{ old('web_reception', (isset($course) ? $course->web_reception->value : null) ) == WebReception::NotAccept ? 'checked' : '' }}
             value="{{ WebReception::NotAccept }}">
      受け付け
    </label>
  </div>
  @if ($errors->has('web_reception')) <p class="help-block">{{ $errors->first('web_') }}</p> @endif
</div>