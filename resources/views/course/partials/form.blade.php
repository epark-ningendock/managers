@php
  use App\Enums\Authority;
  use \App\Enums\Permission;
@endphp

<div class="box box-primary">
  <div></div>
  <div class="box-header with-border">
    <div class="box-tools" data-widget="collapse">
      <button type="button" class="btn btn-sm">
        <i class="fa fa-minus"></i></button>
    </div>
    <h1 class="box-title">検査コースの登録</h1>
  </div>
  <div class="box-body">
    <div class="form-group @if ($errors->has('name')) has-error @endif">
      <label for="name">検査コース名 <span class="text-red">必須</span></label>
      <input type="text" class="form-control" id="name" name="name"
             value="{{ old('name', (isset($course) ? $scourse->name : null)) }}"
             placeholder="検査コース名">
      @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('web_reception')) has-error @endif">
      <label for="web_reception">WEBの受付</label>
      <div class="radio">
        <label>
          <input type="radio" name="web_reception"
                 {{ old('web_reception', (isset($course) ? $course->web_reception : null) ) == '1' ? 'checked' : '' }}
                 value="1">
          受け付ける
        </label>
        <label class="ml-3">
          <input type="radio" name="web_reception"
                 {{ old('web_reception', (isset($course) ? $course->web_reception : null)) == '0' ? 'checked' : '' }}
                 value="0">
          受け付け
        </label>
      </div>
      @if ($errors->has('web_reception')) <p class="help-block">{{ $errors->first('web_') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('calendar_id')) has-error @endif" >
      <label for="calendar_id">カレンダーの設定</label>
      <select name="calendar_id" id="calendar_id" class="form-control">
        <option value="">なし</option>
        {{--@foreach ($calendars as $calendar)--}}
          {{--<option {{ old('calendar_id') == $calendar->id ? 'selected' : '' }}--}}
                  {{--value="{{ $calendar->id }}"> {{ $calendar->name }}</option>--}}
        {{--@endforeach--}}
      </select>
      @if ($errors->has('calendar_id')) <p class="help-block">{{ $errors->first('calendar_id') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('is_category')) has-error @endif">
      <label for="web_reception">コースの種別</label>
      <div class="radio">
        <label>
          <input type="radio" name="is_category"
                 {{ old('is_category', (isset($course) ? $course->is_category : null) ) == '1' ? 'checked' : '' }}
                 value="1">
          通常コース
        </label>
        <label class="ml-3">
          <input type="radio" name="is_category"
                 {{ old('is_category', (isset($course) ? $course->is_category : null)) == '2' ? 'checked' : '' }}
                 value="2">
          健保コース
        </label>
      </div>
      @if ($errors->has('is_category')) <p class="help-block">{{ $errors->first('is_category') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('course_point')) has-error @endif">
      <label for="course_point">コースの特徴</label>
      <textarea class="form-control" id="course_point" name="course_point" rows="5">
        {{ old('course_point', (isset($course) ? $scourse->course_point : null)) }}
      </textarea>
      <span class="pull-right">0/1000文字</span>
      @if ($errors->has('course_point')) <p class="help-block">{{ $errors->first('course_point') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('course_notice')) has-error @endif">
      <label for="course_notice">注意事項</label>
      <textarea class="form-control" id="course_notice" name="course_notice">
        {{ old('course_notice', (isset($course) ? $scourse->course_notice : null)) }}
      </textarea>
      <span class="pull-right">0/1000文字</span>
      @if ($errors->has('course_notice')) <p class="help-block">{{ $errors->first('course_notice') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('course_cancel')) has-error @endif">
      <label for="course_cancel">キャンセルについて</label>
      <textarea class="form-control" id="course_cancel" name="course_cancel">
        {{ old('course_cancel', (isset($course) ? $scourse->course_cancel : null)) }}
      </textarea>
      <span class="pull-right">0/1000文字</span>
      @if ($errors->has('course_cancel')) <p class="help-block">{{ $errors->first('course_cancel') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('reception_start_day') || $errors->has('reception_start_month') || $errors->has('reception_end_day') || $errors->has('reception_end_month')) has-error @endif">
      <label>受付時間 <span class="text-red">必須</span></label>
      <div class="form-horizontal">
          本日から
          <input type="number" id="reception_start_day" name="reception_start_day" class="form-control d-inline-block ml-2" style="width:60px;"
                 value="{{ old('reception_start_day', (isset($course) ? $scourse->reception_start_date : null)) }}" />
          ヶ月
          <input type="number" id="reception_start_month" name="reception_end_date" class="form-control d-inline-block ml-2 mr-2" style="width:60px;"
                 value="{{ old('reception_start_day', (isset($course) ? $scourse->reception_end_date : null)) }}" />
          日間、受付可能。 うち
          <input type="number" id="reception_end_day" name="reception_start_day" class="form-control d-inline-block ml-2 mr-2" style="width:60px;"
                 value="{{ old('reception_end_day', (isset($course) ? $scourse->reception_start_date : null)) }}" />
          ヶ月
          <input type="number" id="reception_end_month" name="reception_start_day" class="form-control d-inline-block ml-2" style="width:60px;"
                 value="{{ old('reception_end_month', (isset($course) ? $scourse->reception_start_date : null)) }}" />
          日間から受付開始。
      </div>
      <div class="mt-2">(事前決済のみ利用の場合、受付期限は90日となります。）</div>
      @if ($errors->has('reception_start_date')) <p class="help-block">{{ $errors->first('reception_start_date') }}</p> @endif
      @if ($errors->has('reception_end_date')) <p class="help-block">{{ $errors->first('reception_end_date') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('cancellation_deadline')) has-error @endif" >
      <label for="cancellation_deadline">変更キャンセル受付期限</label>
      <div>
        受診日
        <select name="cancellation_deadline" id="cancellation_deadline" class="form-control mr-2 d-inline-block" style="width: 60px;">
          @for ($i = 1; $i <= 31; $i++)
            <option {{ old('cancellation_deadline', 6) == $i ? 'selected' : '' }}
                    value="{{ $i }}"> {{ $i }}</option>
          @endfor
        </select>
        日までは変更・キャンセル可能
      </div>
      @if ($errors->has('cancellation_deadline')) <p class="help-block">{{ $errors->first('cancellation_deadline') }}</p> @endif
    </div>

  </div>
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <div class="box-tools" data-widget="collapse">
      <button type="button" class="btn btn-sm">
        <i class="fa fa-minus"></i></button>
    </div>
    <h1 class="box-title">価格の設定</h1>
  </div>
  <div class="box-body">
    <h4 class="d-inline-block">価格</h4> <span class="text-red text-bold">必須</span>
    <div class="form-group @if ($errors->has('is_price') || $errors->has('price')) has-error @endif">
      <label for="name">表示価格</label>
      <div>
        <input type="checkbox" class="checkbox d-inline-block mr-2" name="is_price"
               id="is_price" {{ old('is_price', (isset($course)? $course->is_price : null)) == 1 ? 'checked' : '' }} />
        <label for="is_price">価格</label>
        <input type="number" class="form-control d-inline-block mr-2 ml-2" id="price" name="price" style="width: 100px;"
               value="{{ old('price', (isset($course) ? $course->price : null)) }}">
        円
        <span class="ml-5">０円（税込）</span>
      </div>
      @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('is_price_memo') || $errors->has('price_memo')) has-error @endif">
      <label for="name">手動設定金額</label>
      <div>
        <input type="checkbox" class="checkbox d-inline-block mr-2" name="is_price_memo"
               id="is_price_memo" {{ old('is_price_memo', (isset($course)? $course->is_price_memo : null)) == 1 ? 'checked' : '' }} />
        <label for="is_price_memo">メモ</label>
        <input type="number" class="form-control d-inline-block mr-2 ml-2" id="price_memo" name="price_memo" style="width: 230px;"
               value="{{ old('price_memo', (isset($course) ? $course->price_memo : null)) }}">
      </div>
      @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
    </div>
    <div class="separator mb-3"></div>
    <div class="form-group @if ($errors->has('tax_class')) has-error @endif" >
      <label for="tax_class">税区分<span class="text-red 必須"></span></label>
      <div class="row">
        <div class="col-md-12">
          <select name="tax_class" id="tax_class" class="form-control">
            <option value="">なし</option>
            {{--@foreach ($calendars as $calendar)--}}
            {{--<option {{ old('calendar_id') == $calendar->id ? 'selected' : '' }}--}}
            {{--value="{{ $calendar->id }}"> {{ $calendar->name }}</option>--}}
            {{--@endforeach--}}
          </select>
        </div>
      </div>
      @if ($errors->has('tax_class')) <p class="help-block">{{ $errors->first('tax_class') }}</p> @endif
    </div>

  </div>
</div>

@for($qi = 0; $qi < 5; $qi++)
  @php
    $question = isset($course) ? $course->course_questions()->get($qi - 1) : null;
  @endphp
  <div class="box box-primary">
    <div class="box-header with-border">
      <div class="box-tools" data-widget="collapse">
        <button type="button" class="btn btn-sm">
          <i class="fa fa-minus"></i></button>
      </div>
      <h1 class="box-title">質問・回答の設定</h1>
    </div>
    <div class="box-body">
      <div class="form-group @if ($errors->has('is_question') || $errors->has('is_question')) has-error @endif">
        <label for="name">質問事項の利用</label>
        <div>
          <input type="checkbox" class="checkbox d-inline-block mr-2" {{ old('is_question', isset($question) ? $question->is_question : null) == 1 ? 'checked' : '' }}
                 id="is_question_use_{{$qi}}" name="is_question[]" value="1"/>
          <label for="is_question_use_{{$qi}}">利用する</label>
          <input type="checkbox" class="checkbox d-inline-block mr-2 ml-2" {{ old('is_question', isset($question) ? $question->is_question : null) == 0 ? 'checked' : '' }}
                 id="is_question_not_use_{{$qi}}" name="is_question[]" value="0"/>
          <label for="is_question_not_use_{{$qi}}">利用しない</label>
        </div>
      </div>

      <div class="form-group @if ($errors->has('question_title')) has-error @endif">
        <label for="question_title_{{$qi}}">質問事項タイトル</label>
        <input type="text" class="form-control" id="question_title_{{$qi}}"
               name="question_title[]"/>
      </div>

      <div class="form-group">
        <label for="anser01_{{$qi}}">回答1</label>
        <input type="text" class="form-control" id="answer01_{{$qi}}"
               name="answer01[]"/>
      </div>

      <div class="form-group">
        <label for="anser02_{{$qi}}">回答2</label>
        <input type="text" class="form-control" id="answer02_{{$qi}}"
               name="answer02[]"/>
      </div>

      <div class="form-group">
        <label for="anser03_{{$qi}}">回答3</label>
        <input type="text" class="form-control" id="answer03_{{$qi}}"
               name="answer03[]"/>
      </div>

      <div class="form-group">
        <label for="anser04_{{$qi}}">回答4</label>
        <input type="text" class="form-control" id="answer04_{{$qi}}"
               name="answer04[]"/>
      </div>

      <div class="form-group">
        <label for="anser05_{{$qi}}">回答5</label>
        <input type="text" class="form-control" id="answer05_{{$qi}}"
               name="answer05[]"/>
      </div>

      <div class="form-group">
        <label for="anser06_{{$qi}}">回答6</label>
        <input type="text" class="form-control" id="answer06_{{$qi}}"
               name="answer06[]"/>
      </div>

      <div class="form-group">
        <label for="anser07_{{$qi}}">回答2</label>
        <input type="text" class="form-control" id="answer07_{{$qi}}"
               name="answer07[]"/>
      </div>

      <div class="form-group">
        <label for="anser08_{{$qi}}">回答8</label>
        <input type="text" class="form-control" id="answer08_{{$qi}}"
               name="answer08[]"/>
      </div>

      <div class="form-group">
        <label for="anser09_{{$qi}}">回答9</label>
        <input type="text" class="form-control" id="answer09_{{$qi}}"
               name="answer09[]"/>
      </div>

      <div class="form-group">
        <label for="anser10_{{$qi}}">回答10</label>
        <input type="text" class="form-control" id="answer10_{{$qi}}"
               name="answer10[]"/>
      </div>

    </div>
  </div>
@endfor

<div class="box-primary">
  <div class="box-footer">
    <a href="{{ url()->previous() }}" class="btn btn-default">バック</a>
    <button type="submit" class="btn btn-primary">つくる</button>
  </div>
</div>

<style>
  .d-inline-block {
    display: inline-block;
  }
  .separator {
    margin: 0px -10px;
    height: 0px;
    border-bottom: 1px solid #f4f4f4;
  }
</style>

@section('script')
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // character count
          -----------------------------------------------------*/
          (function () {
              $('textarea').on('keyup', function() {
                  const len = $(this).val().length;
                  if (len >= 1000) {
                      $(this).val($(this).val().substring(0, 999));
                  } else {
                      $(this).next('span').text(len + '/1000文字');
                  }
              });
          })();
      })(jQuery);
  </script>
@stop

  {{--<div class="form-group @if ($errors->has('status')) has-error @endif">--}}
    {{--<label for="status">状態</label>--}}
    {{--<div class="radio">--}}
      {{--<label>--}}
        {{--<input type="radio" name="status"--}}
               {{--{{ old('status', (isset($staff) ? $staff->status->value : null) ) == StaffStatus::Valid ? 'checked' : '' }}--}}
               {{--value="{{ StaffStatus::Valid }}">--}}
        {{--{{ StaffStatus::Valid()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" name="status"--}}
               {{--{{ old('status', (isset($staff) ? $staff->status->value : null)) == StaffStatus::Invalid ? 'checked' : '' }}--}}
               {{--value="{{ StaffStatus::Invalid }}">--}}
        {{--{{ StaffStatus::Invalid()->description }}--}}
      {{--</label>--}}
    {{--</div>--}}
    {{--@if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('name')) has-error @endif">--}}
    {{--<label for="name">スタッフ名</label>--}}
    {{--<input type="text" class="form-control" id="name" name="name"--}}
           {{--value="{{ old('name', (isset($staff) ? $staff->name : null)) }}"--}}
           {{--placeholder="スタッフ名">--}}
    {{--@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('login_id')) has-error @endif">--}}
    {{--<label for="login_id">ログインID</label>--}}
    {{--<input type="text" class="form-control" id="login_id" name="login_id"--}}
           {{--value="{{ old('login_id', (isset($staff) ? $staff->login_id : null)) }}"--}}
           {{--placeholder="ログインID">--}}
    {{--@if ($errors->has('login_id')) <p class="help-block">{{ $errors->first('login_id') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('email')) has-error @endif">--}}
    {{--<label for="email">メールアドレス</label>--}}
    {{--<input type="email" class="form-control" id="email" name="email"--}}
           {{--value="{{ old('email', (isset($staff) ? $staff->email : null)) }}"--}}
           {{--placeholder="メールアドレス">--}}
    {{--@if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('is_hospital')) has-error @endif">--}}
    {{--<label class="mb-0">医療機関管理</label>--}}
    {{--<div class="radio mt-0">--}}
      {{--<label>--}}
        {{--<input type="radio" name="is_hospital" id="is_hospital_none" value="{{ Permission::None }}"--}}
               {{--{{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::None ? 'checked' : '' }}--}}
               {{--class="permission-check">--}}
        {{--{{ Permission::None()->description }}--}}
      {{--</label>--}}
      {{--<label>--}}
        {{--<input type="radio" name="is_hospital" id="is_hospital_view" value="{{ Permission::View }}"--}}
               {{--{{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::View ? 'checked' : '' }}--}}
               {{--class="permission-check">--}}
        {{--{{ Permission::View()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" id="is_hospital_edit" name="is_hospital" value="{{ Permission::Edit }}" class="permission-check"--}}
            {{--{{ old('is_hospital', (isset($staff) ? $staff->staff_auth->is_hospital : -1)) == Permission::Edit ? 'checked' : '' }}>--}}
        {{--{{ Permission::Edit()->description }}--}}
      {{--</label>--}}
    {{--</div>--}}
    {{--@if ($errors->has('is_hospital')) <p class="help-block">{{ $errors->first('is_hospital') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('is_staff')) has-error @endif">--}}
    {{--<label class="mb-0">スタッフ管理</label>--}}
    {{--<div class="radio mt-0">--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_staff_none" name="is_staff" class="permission-check" value="{{ Permission::None  }}"--}}
            {{--{{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::None ? 'checked' : '' }}>--}}
        {{--{{ Permission::None()->description }}--}}
      {{--</label>--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_staff_view" name="is_staff" class="permission-check" value="{{ Permission::View  }}"--}}
            {{--{{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::View ? 'checked' : '' }}>--}}
        {{--{{ Permission::View()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" id="is_staff_edit" name="is_staff" value="{{ Permission::Edit }}" class="permission-check"--}}
            {{--{{ old('is_staff', (isset($staff) ? $staff->staff_auth->is_staff : -1)) == Permission::Edit ? 'checked' : '' }}>--}}
        {{--{{ Permission::Edit()->description }}--}}
      {{--</label>--}}
    {{--</div>--}}
    {{--@if ($errors->has('is_staff')) <p class="help-block">{{ $errors->first('is_staff') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('is_item_category')) has-error @endif">--}}
    {{--<label class="mb-0">検査コース分類</label>--}}
    {{--<div class="radio mt-0">--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_item_category_none" name="is_item_category" value="{{ Permission::None }}" class="permission-check"--}}
            {{--{{ old('is_item_category', (isset($staff) ? $staff->staff_auth->is_item_category : -1)) == Permission::None ? 'checked' : '' }}--}}
        {{-->--}}
        {{--{{ Permission::None()->description }}--}}
      {{--</label>--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_item_category_view" name="is_item_category" value="{{ Permission::View }}" class="permission-check"--}}
            {{--{{ old('is_item_category', (isset($staff) ? $staff->staff_auth->is_item_category : -1)) == Permission::View ? 'checked' : '' }}--}}
        {{-->--}}
        {{--{{ Permission::View()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" id="is_item_category_edit" name="is_item_category" value="{{ Permission::Edit }}" class="permission-check"--}}
            {{--{{ old('is_item_category', (isset($staff) ? $staff->staff_auth->is_item_category : -1)) == Permission::Edit ? 'checked' : '' }}>--}}
        {{--{{ Permission::Edit()->description }}--}}
      {{--</label>--}}
    {{--</div>--}}
    {{--@if ($errors->has('is_item_category')) <p--}}
        {{--class="help-block">{{ $errors->first('is_item_category') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('is_invoice')) has-error @endif">--}}
    {{--<label class="mb-0">請求管理</label>--}}
    {{--<div class="radio mt-0">--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_invoice_none" name="is_invoice" value="{{ Permission::None }}" class="permission-check"--}}
            {{--{{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::None ? 'checked' : '' }}>--}}
        {{--{{ Permission::None()->description }}--}}
      {{--</label>--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_invoice_view" name="is_invoice" value="{{ Permission::View }}" class="permission-check"--}}
            {{--{{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::View ? 'checked' : '' }}>--}}
        {{--{{ Permission::View()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" id="is_invoice_edit" name="is_invoice" value="{{ Permission::Edit }}" class="permission-check"--}}
            {{--{{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::Edit ? 'checked' : '' }}>--}}
        {{--{{ Permission::Edit()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" id="is_invoice_upload" name="is_invoice" value="{{ Permission::Upload }}" class="permission-check"--}}
            {{--{{ old('is_invoice', (isset($staff) ? $staff->staff_auth->is_invoice : -1)) == Permission::Upload ? 'checked' : '' }}>--}}
        {{--{{ Permission::Upload()->description }}--}}
      {{--</label>--}}
    {{--</div>--}}
    {{--@if ($errors->has('is_invoice')) <p class="help-block">{{ $errors->first('is_invoice') }}</p> @endif--}}
  {{--</div>--}}

  {{--<div class="form-group @if ($errors->has('is_pre_account')) has-error @endif">--}}
    {{--<label class="mb-0">事前決済管理</label>--}}
    {{--<div class="radio mt-0">--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_pre_account_none" name="is_pre_account" value="{{ Permission::None }}" class="permission-check"--}}
            {{--{{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::None ? 'checked' : '' }}>--}}
        {{--{{ Permission::None()->description }}--}}
      {{--</label>--}}
      {{--<label>--}}
        {{--<input type="radio" id="is_pre_account_view" name="is_pre_account" value="{{ Permission::View }}" class="permission-check"--}}
            {{--{{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::View ? 'checked' : '' }}>--}}
        {{--{{ Permission::View()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" id="is_pre_account_edit" name="is_pre_account" value="{{ Permission::Edit }}" class="permission-check"--}}
            {{--{{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::Edit ? 'checked' : '' }}>--}}
        {{--{{ Permission::Edit()->description }}--}}
      {{--</label>--}}
      {{--<label class="ml-3">--}}
        {{--<input type="radio" id="is_pre_account_upload" name="is_pre_account" value="{{ Permission::Upload }}" class="permission-check"--}}
            {{--{{ old('is_pre_account', (isset($staff) ? $staff->staff_auth->is_pre_account : -1)) == Permission::Upload ? 'checked' : '' }}>--}}
        {{--{{ Permission::Upload()->description }}--}}
      {{--</label>--}}
    {{--</div>--}}
    {{--@if ($errors->has('is_pre_account')) <p class="help-block">{{ $errors->first('is_pre_account') }}</p> @endif--}}
  {{--</div>--}}
