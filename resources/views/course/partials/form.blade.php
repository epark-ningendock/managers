@php
  use \App\Enums\WebReception;
  use \App\Enums\CourseImageType;
  use \App\Enums\Authority;
  use App\Enums\RegistrationDivision;

  if(isset($course)) {
    $course_details = $course->course_details;
    $course_options = $course->course_options;
    $course_questions = $course->course_questions;
  }

  $o_option_ids = collect(old('option_ids', []));
  $o_minor_ids = collect(old('minor_ids'));
  $o_minor_values = collect(old('minor_values'));
  $o_is_questions = collect(old('is_questions', []));
  $o_question_titles = collect(old('question_titles', []));
  $o_answer01s = collect(old('answer01s', []));
  $o_answer02s = collect(old('answer02s', []));
  $o_answer03s = collect(old('answer03s', []));
  $o_answer04s = collect(old('answer04s', []));
  $o_answer05s = collect(old('answer05s', []));
  $o_answer06s = collect(old('answer06s', []));
  $o_answer07s = collect(old('answer07s', []));
  $o_answer08s = collect(old('answer08s', []));
  $o_answer09s = collect(old('answer09s', []));
  $o_answer10s = collect(old('answer10s', []));
@endphp
@include('layouts.partials.error_pan')
<div class="box box-primary">
  <div></div>
  <div class="box-header with-border">
    <div class="box-tools" data-widget="collapse">
      <button type="button" class="btn btn-sm">
        <i class="fa fa-minus"></i></button>
    </div>
    <h1 class="box-title">検査コースの登録</h1>
  </div>
  <div class="form-entry">
    <div class="box-body">
    <input type="hidden" name="lock_version" value="{{ $course->lock_version or ''}}" />
    <input type="hidden" name="hospital_id" value="{{ session()->get('hospital_id') }}" />
    <div class="form-group @if ($errors->has('name')) has-error @endif">
      <label for="name">検査コース名
        <span class="form_required">必須</span>
      </label>
      <input type="text" class="form-control w16em" id="name" name="name"
             value="{{ old('name', (isset($course) ? $course->name : null)) }}"
             placeholder="検査コース名">
      @if ($errors->has('name')) <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('name') }}</p> @endif
    </div>


    <div class="form-group py-sm-2">
        <label for="web_reception">WEB公開</label>
        <group class="inline-radio two-option">
            <div>
                <input type="radio" name="web_reception" {{ old('web_reception', (isset($course) ? $course->web_reception->value : null) ) == WebReception::ACCEPT ? 'checked' : 'checked' }}
                value="{{ WebReception::ACCEPT }}"
                ><label>{{ WebReception::ACCEPT()->description }}</label></div>
            <div>
                <input type="radio" name="web_reception" {{ old('web_reception', (isset($course) ? $course->web_reception->value : null)) == WebReception::NOT_ACCEPT ? 'checked' : '' }}
                value="{{ WebReception::NOT_ACCEPT }}"><label>{{ WebReception::NOT_ACCEPT()->description }}</label></div>
        </group>
        @if ($errors->has('web_reception')) <p class="help-block has-error">{{ $errors->first('web_reception') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('calendar_id')) has-error @endif" >
      <label for="calendar_id">カレンダーの設定</label>
      <select name="calendar_id" id="calendar_id" class="form-control w20em" >
        <option value="">なし</option>
        @foreach ($calendars as $calendar)
          <option {{ old('calendar_id', isset($course) ? $course->calendar_id : null) == $calendar->id ? 'selected' : '' }}
                  value="{{ $calendar->id }}"> {{ $calendar->name }}</option>
        @endforeach
      </select>
      @if ($errors->has('calendar_id')) <p class="help-block">{{ $errors->first('calendar_id') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('is_category')) has-error @endif">
        <fieldset class="form-group">
            <legend class="mb-0">コースの種別</legend>
            <div class="radio">
                <input type="radio" name="is_category" id="is_category_1" value="1"
                       {{ old('is_category', (isset($course) ? $course->is_category : null) ) == 1 ? 'checked' : 'checked' }}
                       class="permission-check">
                <label for="is_category_1" class="radio-label">通常コース</label>
            </div>
            <div class="radio">
                <input type="radio" id="is_category_2" name="is_category" value="2" class="permission-check"
                        {{ old('is_category', (isset($course) ? $course->is_category : null) ) == 2 ? 'checked' : '' }}>
                <label for="is_category_2" class="radio-label">健保コース</label>
            </div>
            @if ($errors->has('is_category')) <p class="help-block has-error">{{ $errors->first('is_hospital') }}</p> @endif
        </fieldset>
    </div>

    <div class="form-group">
      <legend>コース画像</legend>
      <div class="row">
        <div class="col-sm-4">
          {{Form::label('course_image_main', '検査コースメイン' , ['class' => 'form_label'])}}
          @if (isset($course) && !is_null($course->course_images) && !is_null($course->course_images->where('type', CourseImageType::MAIN)->first()))
            <div class="sub_image_area">
              <img class="object-fit" src="{{$course->course_images->where('type', CourseImageType::MAIN)->first()->path}}">
              <p class="file_delete_text">
                <a onclick="return confirm('この画像を削除します、よろしいですか？')"
                   class="btn btn-mini btn-danger"
                   href="{{ route('course.image.delete', [
                     'course_image_id' => $course->course_images()->where('type', CourseImageType::MAIN)->first()->id
                   ]) }}"
                >
                  ファイル削除
                </a>
              </p>
            </div>
          @else
            <div class="sub_image_area">
              <img src="/img/no_image.png">
            </div>
          @endif
          <label class="file-upload btn btn-primary">
            ファイル選択 {{Form::file("course_image_main", ['class' => 'field', 'accept' => 'image/*'])}}
          </label>
          @if ($errors->has('course_image_main'))
            <div class="error_message">
              {{ $errors->first('course_image_main') }}
            </div>
          @endif
        </div>
        <div class="col-sm-4">
          {{Form::label('course_image_pc', '受診の流れメイン（PC）' , ['class' => 'form_label'])}}
          @if (isset($course) && !is_null($course->course_images) && !is_null($course->course_images->where('type', CourseImageType::PC)->first()))
            <div class="sub_image_area">
              <img class="object-fit" src="{{$course->course_images->where('type', CourseImageType::PC)->first()->path}}">
              <p class="file_delete_text">
                <a onclick="return confirm('この画像を削除します、よろしいですか？')"
                    class="btn btn-mini btn-danger"
                    href="{{ route('course.image.delete', [
                      'course_image_id' => $course->course_images()->where('type', CourseImageType::PC)->first()->id
                    ]) }}"
                >
                  ファイル削除
                </a>
              </p>
            </div>
          @else
            <div class="sub_image_area">
              <img src="/img/no_image.png">
            </div>
          @endif
          <label class="file-upload btn btn-primary">
            ファイル選択 {{Form::file("course_image_pc", ['class' => 'field', 'accept' => 'image/*'])}}
          </label>
          @if ($errors->has('course_image_pc'))
            <div class="error_message">
              {{ $errors->first('course_image_pc') }}
            </div>
          @endif
        </div>
        <div class="col-sm-4">
          {{Form::label('course_image_sp', '受診の流れメイン（SP）' , ['class' => 'form_label'])}}
          @if (isset($course) && !is_null($course->course_images) && !is_null($course->course_images->where('type', CourseImageType::SP)->first()))
            <div class="sub_image_area">
              <img class="object-fit" src="{{$course->course_images->where('type', CourseImageType::SP)->first()->path}}">
              <p class="file_delete_text">
                <a onclick="return confirm('この画像を削除します、よろしいですか？')"
                  class="btn btn-mini btn-danger"
                  href="{{ route('course.image.delete', [
                    'course_image_id' => $course->course_images()->where('type', CourseImageType::SP)->first()->id
                  ]) }}"
                >
                  ファイル削除
                </a>
              </p>
            </div>
          @else
            <div class="sub_image_area">
              <img src="/img/no_image.png">
            </div>
          @endif
          <label class="file-upload btn btn-primary">
            ファイル選択 {{Form::file("course_image_sp", ['class' => 'field', 'accept' => 'image/*'])}}
          </label>
          @if ($errors->has('course_image_sp'))
            <div class="error_message">
              {{ $errors->first('course_image_sp') }}
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="course_point">コースの特徴</label>
      <textarea class="form-control" id="course_point" name="course_point" rows="5">{{ old('course_point', (isset($course) ? $course->course_point : null)) }}</textarea>
      <span class="pull-right">0/1000文字</span>
    </div>

    <div class="form-group">
      <label for="course_notice">注意事項</label>
      <textarea class="form-control" id="course_notice" name="course_notice">{{ old('course_notice', (isset($course) ? $course->course_notice : null)) }}</textarea>
      <span class="pull-right">0/1000文字</span>
    </div>

    <div class="form-group">
      <label for="course_cancel">キャンセルについて</label>
      <textarea class="form-control" id="course_cancel" name="course_cancel">{{ old('course_cancel', (isset($course) ? $course->course_cancel : null)) }}</textarea>
      <span class="pull-right">0/1000文字</span>
    </div>

     <div class="form-group @if ($errors->has('publish_start_date') or $errors->has('publish_end_date')) has-error @endif">
         <label>コース表示期間</label>
         <div class="form-horizontal display-period">
             <span>表示開始</span>
                {{ Form::text('publish_start_date', old('publish_start_date', (isset($course) ? $course->publish_start_date : (isset($disp_date_start) ? $disp_date_start : null))),
                    ['class' => 'd-inline-block w16em form-control', 'id' => 'datetimepicker-disp-start', 'placeholder' => $disp_date_start]) }}
             <span>表示終了</span>
                {{ Form::text('publish_end_date', old('publish_end_date', (isset($course) ? $course->publish_end_date : (isset($disp_date_end) ? $disp_date_end : null))),
                    ['class' => 'd-inline-block w16em form-control', 'id' => 'datetimepicker-disp-end', 'placeholder' => $disp_date_end]) }}
        </div>
        @if ($errors->has('publish_start_date'))
          <p class="help-block">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('publish_start_date') }}
          </p>
        @endif
        @if ($errors->has('publish_end_date'))
          <p class="help-block">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('publish_end_date') }}
          </p>
        @endif
     </div>

    <div class="form-group">
      <label>受付期間 <span class="form_required">必須</span></label>
      <div class="form-horizontal">
          本日から
          <div class="d-inline-block @if ($errors->has('reception_start_day')) has-error @endif" >
              <input type="number" id="reception_start_month" name="reception_start_month" class="form-control d-inline-block ml-2" style="width:60px;"
                     value="{{ old('reception_start_month', (isset($course) ? intdiv($course->reception_start_date, 1000) : 6)) }}" />
          </div>
          ヶ月
          <div class="d-inline-block @if ($errors->has('reception_start_month')) has-error @endif" >
              <input type="number" id="reception_start_day" name="reception_start_day" class="form-control d-inline-block ml-2 mr-2" style="width:60px;"
                     value="{{ old('reception_start_day', (isset($course) ? $course->reception_start_date%1000 : 0)) }}" />
          </div>
          日間、受付可能。 うち
          <div class="d-inline-block @if ($errors->has('reception_end_day')) has-error @endif" >
              <input type="number" id="reception_end_month" name="reception_end_month" class="form-control d-inline-block ml-2 mr-2" style="width:60px;"
                     value="{{ old('reception_end_month', (isset($course) ? intdiv($course->reception_end_date, 1000) : 0)) }}" />
          </div>
          ヶ月
          <div class="d-inline-block @if ($errors->has('reception_end_month')) has-error @endif" >
              <input type="number" id="reception_end_day" name="reception_end_day" class="form-control d-inline-block ml-2" style="width:60px;"
                     value="{{ old('reception_end_day', (isset($course) ? $course->reception_end_date%1000 : 7)) }}" />
          </div>
          日間から受付開始。
      </div>
      <div class="mt-2">(事前決済のみ利用の場合、受付期限は90日となります。）</div>
      @if ($errors->has('reception_start_day')) <p class="help-block text-red">{{ $errors->first('reception_start_day') }}</p>@endif
      @if ($errors->has('reception_start_month')) <p class="help-block text-red">{{ $errors->first('reception_start_month') }}</p> @endif
      @if ($errors->has('reception_end_day')) <p class="help-block text-red">{{ $errors->first('reception_end_day') }}</p> @endif
      @if ($errors->has('reception_end_month')) <p class="help-block text-red">{{ $errors->first('reception_end_month') }}</p> @endif
    </div>


        <div class="form-group @if ($errors->has('reception_acceptance_day_end')) has-error @endif">
            <div class="form-horizontal display-period">
                <label>受付終了日</label>
                {{ Form::text('reception_acceptance_day_end', old('reception_acceptance_day_end', (isset($course) ? $course->reception_acceptance_day_end : null)),
                    ['class' => 'd-inline-block w16em form-control', 'id' => 'reception_acceptance_day_end', 'placeholder' => $disp_date_end]) }}
            </div>
        </div>


    <!--<div class="form-group">
      <label>受付許可日  <span class="form_required">必須</span></label>
      <div class="form-horizontal">
          本日から
          <div class="d-inline-block @if ($errors->has('reception_acceptance_day')) has-error @endif" >
              <input type="number" id="reception_acceptance_day" name="reception_acceptance_day" class="form-control d-inline-block ml-2" style="width:60px;"
                     value="{{ old('reception_acceptance_day', (isset($course) ? $course->reception_acceptance_date % 1000 : 5)) }}" />
          </div>
          ヶ月
          <div class="d-inline-block @if ($errors->has('reception_acceptance_month')) has-error @endif" >
              <input type="number" id="reception_acceptance_month" name="reception_acceptance_month" class="form-control d-inline-block ml-2 mr-2" style="width:60px;"
                     value="{{ old('reception_acceptance_month', (isset($course) ? intdiv($course->reception_acceptance_date, 1000) : 0)) }}" />
          </div>
      </div>
      @if ($errors->has('reception_acceptance_day')) <p class="help-block text-red">{{ $errors->first('reception_acceptance_day') }}</p>@endif
      @if ($errors->has('reception_acceptance_month')) <p class="help-block text-red">{{ $errors->first('reception_acceptance_month') }}</p> @endif
    </div>-->
    <div class="form-group @if ($errors->has('cancellation_deadline')) has-error @endif" >
      <label for="cancellation_deadline">変更キャンセル受付期限</label>
      <div>
        受診日
        <select name="cancellation_deadline" id="cancellation_deadline" class="form-control mr-2 d-inline-block" style="width: 60px;">
          @for ($i = 1; $i <= 31; $i++)
            <option {{ old('cancellation_deadline', isset($course) ? $course->cancellation_deadline : 20) == $i ? 'selected' : '' }}
                    value="{{ $i }}"> {{ $i }}</option>
          @endfor
        </select>
        日までは変更・キャンセル可能
      </div>
      @if ($errors->has('cancellation_deadline')) <p class="help-block">{{ $errors->first('cancellation_deadline') }}</p> @endif
    </div>

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
  <div class="form-entry">
    <div class="box-body">
    <h4 class="d-inline-block">価格</h4> <span class="form_required">必須</span>
    <div class="form-group @if ($errors->has('price')) has-error @endif">
      <label for="name">表示価格（税込）</label>
      <div>
        <input type="checkbox" class="checkbox d-inline-block mr-2" name="is_price" value="1"
               id="is_price" {{ old('is_price', (isset($course)? $course->is_price : null)) == 1 ? 'checked' : '' }} />
        <label for="is_price">価格</label>
        <input type="hidden" id="tax_rate" value="{{ $tax_class->rate }}" />
        <input type="number" class="form-control d-inline-block mr-2 ml-2" id="price" name="price" style="width: 100px;"
               value="{{ old('price', (isset($course) ? $course->price : null)) }}">
        円
      </div>
      @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
    </div>

    <div class="form-group @if ( $errors->has('price_memo')) has-error @endif">
      <label for="price_memo">手動設定金額</label>
      <div>
        <input type="checkbox" class="checkbox d-inline-block mr-2" name="is_price_memo" value="1"
               id="is_price_memo" {{ old('is_price_memo', (isset($course)? $course->is_price_memo : null)) == 1 ? 'checked' : '' }} />
        <label for="is_price_memo">メモ</label>
        <input type="text" class="form-control d-inline-block mr-2 ml-2" id="price_memo" name="price_memo" style="width: 230px;"
               value="{{ old('price_memo', (isset($course) ? $course->price_memo : null)) }}">
      </div>
      @if ($errors->has('price_memo')) <p class="help-block">{{ $errors->first('price_memo') }}</p> @endif
    </div>
  </div>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="box-tools" data-widget="collapse">
      <button type="button" class="btn btn-sm">
        <i class="fa fa-minus"></i></button>
    </div>
    <h1 class="box-title">事前決済の設定</h1>
  </div>
  <div class="form-entry">
    <div class="box-body">
      <div class="form-group @if ($errors->has('is_pre_account')) has-error @endif">
        <label>事前決済価格</label>
        <div>
          <input type="hidden" name="is_pre_account" value="0" />
          <input type="checkbox" id="is_pre_account" name="is_pre_account" value="1"
                 {{ (old('is_pre_account', (isset($course) ? $course->is_pre_account : null) ) == 1 && $is_presettlement) ? 'checked' : '' }}
                 @if(!$is_presettlement) disabled @endif>
          <label for="is_pre_account">利用する</label>
          @if ($errors->has('is_pre_account')) <p class="help-block has-error">{{ $errors->first('is_pre_account') }}</p> @endif
        </div>
      </div>

      <div class="form-group">
        <label>割引率</label>
        <p>{{ $hospital->pre_account_discount_rate }}%
          <input type="hidden" value="{{ $hospital->pre_account_discount_rate }}" id="pre_account_discount_rate" />
        </p>
      </div>

      <div class="form-group">
        <lablel>値引率自動適用</lablel>
        <div>
          <input type="hidden" name="auto_calc_application" value="0" />
          <input type="checkbox" id="auto_calc_application" name="auto_calc_application" value="1"
              {{ old('auto_calc_application', (isset($course) ? $course->auto_calc_application : 1) ) == 1 ? 'checked' : '' }}/>
          <label for="auto_calc_application">利用する</label>
        </div>
      </div>
      <div class="form-group @if ($errors->has('pre_account_price')) has-error @endif">
        <label>事前決済価格</label>
        <div class="form-horizontal">
          <input type="number" class="d-inline-block form-control w16em" id="pre_account_price" name="pre_account_price"
                 value="{{ old('pre_account_price', (isset($course) ? $course->pre_account_price : null)) }}"
                 placeholder="10000"> 円
        </div>
        @if ($errors->has('pre_account_price')) <p class="help-block has-error">{{ $errors->first('pre_account_price') }}</p> @endif
      </div>
    </div>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="box-tools" data-widget="collapse">
      <button type="button" class="btn btn-sm">
        <i class="fa fa-minus"></i></button>
    </div>
    <h1 class="box-title">オプションの設定</h1>
  </div>

  <div class="form-entry">
    <div class="box-body" id="option-setting">
    <table class="table no-border table-hover table-striped ">
      <tr>
        <td class="option-name"><span>オプション名</span></td>
        <td class="option-price">価格</td>
      </tr>
      @foreach($options as $option)
        <tr>
        @php
          $is_checked = false;
          if ($o_option_ids->isNotEmpty()) {
            $is_checked = $o_option_ids->contains($option->id);
          } else if(isset($course_options)) {
            $is_checked = $course_options->where('option_id', $option->id)->isNotEmpty();
          }
        @endphp
          <td class="option-name">
              <input type="checkbox" id="option_set_price{{ $option->id }}" name="option_ids[]" value="{{ $option->id }}" {{ $is_checked ? 'checked' : '' }}/>
              <label class="mr-2" for="option_set_price{{ $option->id }}">{{ $option->name }}</label></td>
          <td class="option-price">{{ number_format($option->price) }} 円</td>
        </tr>
      @endforeach
    </table>
  </div>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="box-tools" data-widget="collapse">
      <button type="button" class="btn btn-sm">
        <i class="fa fa-minus"></i></button>
    </div>
    <h1 class="box-title">設定項目</h1>
  </div>
    <div class="form-entry">
    <div class="box-body" id="setting-list">
      @foreach($majors as $key => $major)
        @foreach($major->middle_classifications as $middle)
          @if(!isset($last) || $major != $last)
            <h4 class="d-inline-block">{{ $major->name }}</h4>
            @php
              $last = $major;
            @endphp
          @endif
          <fieldset>
          <legend>{{ $middle->name }}</legend>
          <div class="row mb-4">
            @foreach($middle->minors_with_fregist_order as $index => $minor)
              @php
                $minor_value = '';
                if($o_minor_ids->isNotEmpty()) {
                  $search_index = $o_minor_ids->search(function($m_id) use ($minor) {
                    return $m_id == $minor->id;
                  });
                  if ($search_index >= 0) {
                    $minor_value = $o_minor_values[$search_index];
                  }
                } else if (isset($course_details)) {
                  $temp = $course_details->where('minor_classification_id', $minor->id)->flatten(1);
                  if ($temp->isNotEmpty()) {
                    $minor_value = $minor->is_fregist == RegistrationDivision::CHECK_BOX ? $temp[0]->select_status : $temp[0]->inputstring;
                  }
                }
              @endphp
                  <input type="hidden" name="minor_ids[]" value="{{ $minor->id }}" />
              @if($minor->is_fregist == RegistrationDivision::CHECK_BOX)
                <p class="col-sm-4">
                <input type="checkbox" class="checkbox d-inline-block minor-checkbox" name="minor_values[]"
                       id="{{ 'minor_id_'.$minor->id }}"
                       {{ $minor_value == 1 ? 'checked' : '' }} value="1" />
                <label class="mr-2" for="{{ 'minor_id_'.$minor->id }}">{{ $minor->name }}</label></p>
              @else
                <p class="col-sm-12">
                    @if($minor->max_length >= 500 )
                        <textarea class="form-control" name="minor_values[]" rows="7">{{ $minor_value }}</textarea>
                    @else
                        <input type="text" name="minor_values[]"
                               class="form-control minor-text minor-text-{{$key}} @if ($index > 0) mt-2 @endif" data-maxlength="{{ $minor->max_length }}"
                               value = "{{ $minor_value }}" />
                    @endif
                <span class="pull-right">0/{{ $minor->max_length }}文字</span>
                </p>
              @endif
            @endforeach
          </div>
          </fieldset>
        @endforeach
      @endforeach
    </div>
    </div>
  </div>

@for($qi = 0; $qi < 5; $qi++)
  @php
    $question = isset($course_questions) ? $course_questions[$qi] : null;
    $is_question = '0';
    $question_title = '';
    $answer01 = '';
    $answer02 = '';
    $answer03 = '';
    $answer04 = '';
    $answer05 = '';
    $answer06 = '';
    $answer07 = '';
    $answer08 = '';
    $answer09 = '';
    $answer10 = '';
    if ($o_is_questions->isNotEmpty()) {
      $is_question = $o_is_questions[$qi];
      $question_title = $o_question_titles->get($qi);
      $answer01 = $o_answer01s->get($qi);
      $answer02 = $o_answer02s->get($qi);
      $answer03 = $o_answer03s->get($qi);
      $answer04 = $o_answer04s->get($qi);
      $answer05 = $o_answer05s->get($qi);
      $answer06 = $o_answer06s->get($qi);
      $answer07 = $o_answer07s->get($qi);
      $answer08 = $o_answer08s->get($qi);
      $answer09 = $o_answer09s->get($qi);
      $answer10 = $o_answer10s->get($qi);
    } else if(isset($question)) {
      $is_question = $question->is_question;
      $question_title = $question->question_title;
      $answer01 = $question->answer01;
      $answer02 = $question->answer02;
      $answer03 = $question->answer03;
      $answer04 = $question->answer04;
      $answer05 = $question->answer05;
      $answer06 = $question->answer06;
      $answer07 = $question->answer07;
      $answer08 = $question->answer08;
      $answer09 = $question->answer09;
      $answer10 = $question->answer10;
    }
  @endphp
  <div class="box box-primary">
    <div class="box-header with-border">
      <div class="box-tools" data-widget="collapse">
        <button type="button" class="btn btn-sm">
          <i class="fa fa-minus"></i></button>
      </div>
      <h1 class="box-title">{{ $qi + 1 }}. 質問・回答の設定</h1>
    </div>

    <div class="form-entry">
        <div class="box-body">
            <div class="form-group py-sm-2">
                <label for="status">状態</label>
                <group class="inline-radio two-option" style="width: 200px;">
                    <div class="status-btn">
                        <input type="radio" class="checkbox d-inline-block mr-2 is_question" name="is_question_{{ $qi }}" {{ $is_question == 1 ? 'checked' : '' }}
                        value="1"
                        ><label>利用する</label>
                    </div>
                    <div class="status-btn">
                        <input type="radio" class="checkbox d-inline-block mr-2 ml-2 is_question" name="is_question_{{ $qi }}" {{ $is_question == 0 ? 'checked' : '' }}
                        value="0"><label>利用しない</label>
                    </div>
                    <input type="hidden" class="hidden-q" value="{{ $is_question }}" name="is_questions[]"/>
                </group>
                @if ($errors->has('is_question_'.$qi)) <p class="help-block has-error">{{ $errors->first('is_question_'.$qi) }}</p> @endif
            </div>

      <div class="form-group">
        <label for="question_title_{{$qi}}">質問事項タイトル</label>
        <input type="text" class="form-control" id="question_title_{{$qi}}"
               value = "{{ $question_title }}" name="question_titles[]"/>
      </div>

      <div class="form-group">
        <label for="anser01_{{$qi}}">回答1</label>
        <input type="text" class="form-control" id="answer01_{{$qi}}"
               value = "{{ $answer01 }}"
               name="answer01s[]"/>
      </div>

      <div class="form-group">
        <label for="anser02_{{$qi}}">回答2</label>
        <input type="text" class="form-control" id="answer02_{{$qi}}"
               value = "{{ $answer02 }}"
               name="answer02s[]"/>
      </div>

      <div class="form-group">
        <label for="anser03_{{$qi}}">回答3</label>
        <input type="text" class="form-control" id="answer03_{{$qi}}"
               value = "{{ $answer03 }}"
               name="answer03s[]"/>
      </div>

      <div class="form-group">
        <label for="anser04_{{$qi}}">回答4</label>
        <input type="text" class="form-control" id="answer04_{{$qi}}"
               value = "{{ $answer04 }}"
               name="answer04s[]"/>
      </div>

      <div class="form-group">
        <label for="anser05_{{$qi}}">回答5</label>
        <input type="text" class="form-control" id="answer05_{{$qi}}"
               value = "{{ $answer05 }}"
               name="answer05s[]"/>
      </div>

      <div class="form-group">
        <label for="anser06_{{$qi}}">回答6</label>
        <input type="text" class="form-control" id="answer06_{{$qi}}"
               value = "{{ $answer06 }}"
               name="answer06s[]"/>
      </div>

      <div class="form-group">
        <label for="anser07_{{$qi}}">回答7</label>
        <input type="text" class="form-control" id="answer07_{{$qi}}"
               value = "{{ $answer07 }}"
               name="answer07s[]"/>
      </div>

      <div class="form-group">
        <label for="anser08_{{$qi}}">回答8</label>
        <input type="text" class="form-control" id="answer08_{{$qi}}"
               value = "{{ $answer08 }}"
               name="answer08s[]"/>
      </div>

      <div class="form-group">
        <label for="anser09_{{$qi}}">回答9</label>
        <input type="text" class="form-control" id="answer09_{{$qi}}"
               value = "{{ $answer09 }}"
               name="answer09s[]"/>
      </div>

      <div class="form-group">
        <label for="anser10_{{$qi}}">回答10</label>
        <input type="text" class="form-control" id="answer10_{{$qi}}"
               value = "{{ $answer10 }}"
               name="answer10s[]"/>
      </div>

    </div>
    </div>
  </div>
@endfor

<div class="box-primary">
  <div class="box-footer">
    <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
    <button type="submit" class="btn btn-primary">保存</button>
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
          $('.status-btn').on('click', function() {
              const is_q_val = $(this).find('.is_question').val();
              $(this).parent().find('.hidden-q').val(is_q_val);
          });
          /* ---------------------------------------------------
          // character count
          -----------------------------------------------------*/
          (function () {
              const textAreaChange = function(ele, max) {
                  const len = ele.val().length;

                  if (len > max) {
                      ele.val(ele.val().substring(0, max));
                  } else {
                      ele.next('span').text(len + '/' + max + '文字');
                  }
              };

              $('textarea').each(function(index, ele) {

                  ele = $(ele);
                  ele.on('keyup', function() {
                    textAreaChange(ele, 1000);
                  });
                  textAreaChange(ele, 1000);
              });


              $('.minor-text').on('keyup', function() {
                  const max = parseInt($(this).data('maxlength'));
                  textAreaChange($(this), max);
              });
              @for ($i = 0; $i <= $majors->count(); $i++)
              if($('.minor-text-{{$i}}').length){
                  textAreaChange($('.minor-text-{{$i}}'), parseInt($('.minor-text-{{$i}}').data('maxlength')));
              }
              @endfor
          })();

          /* ---------------------------------------------------
          // minor checkbox values
          -----------------------------------------------------*/

          (function () {
              const change = function(ele) {
                  if (ele.prop('checked')) {
                      if (ele.next().next().attr('class') == 'dummy') {
                        ele.next().next().remove();
                      }
                  } else {
                    $('<input type="hidden" class="dummy" name="minor_values[]" value="0"/>').insertAfter(ele.next('label'));
                  }
              };

              $('.minor-checkbox').each(function(index, ele) {
                  ele = $(ele);
                  ele.change(function() {
                      change(ele);
                  });
                  change(ele);
              });
          })();

          /* ---------------------------------------------------
          // price enable/disable
          -----------------------------------------------------*/
          (function () {
              const change = function() {
                  if($('#is_price').prop('checked')) {
                      $('#price').prop('disabled', false);
                  } else {
                      $('#price').prop('disabled', true);
                  }
                  $('#auto_calc_application').change();
              };
              change();
              $('#is_price').change(change);
          })();

          /* ---------------------------------------------------
          // price memo enable/disable
          -----------------------------------------------------*/
          (function () {
              const change = function() {
                  if($('#is_price_memo').prop('checked')) {
                      $('#price_memo').prop('disabled', false);
                  } else {
                      $('#price_memo').prop('disabled', true);
                  }
              };
              change();
              $('#is_price_memo').change(change);
          })();

          /* ---------------------------------------------------
          // is_question values
          -----------------------------------------------------*/
          (function () {
              const change = function(ele) {
                  ele.siblings('input:hidden').val(ele.val());
              };

              $('.is_question').each(function(index, ele){
                  ele = $(ele);
                  ele.change(function() {
                      change(ele);
                  });
                  change(ele);
              })
          })();

          /* ---------------------------------------------------
          // is_pre_acc change
          -----------------------------------------------------*/
          (function(){
              const change = function() {
                  if($('#is_pre_account').prop('checked')) {
                      $('#auto_calc_application').prop('checked', true);
                      $('#auto_calc_application, #pre_account_price').prop('disabled', false);
                  } else {
                      $('#auto_calc_application').prop('checked', false);
                      $('#auto_calc_application, #pre_account_price').prop('disabled', true);
                  }
                  $('#auto_calc_application').change();
              };
              $('#is_pre_account').change(change);
          })();

          /* ---------------------------------------------------
          // auto_calc_application change
          -----------------------------------------------------*/
          (function(){
              const change = function() {
                  if($('#auto_calc_application').prop('checked')) {
                      const price = $('#is_price').prop('checked') ? ($('#price').val() || 0) : 0;
                      const discountRate = $('#pre_account_discount_rate').val() || 0;
                      let accPrice = price * (discountRate/100);
                      $('#pre_account_price').val(accPrice);
                      $('#pre_account_price').prop('disabled', true);
                  } else {
                      $('#pre_account_price').val('');
                      if($('#is_pre_account').prop('checked')) {
                          $('#pre_account_price').prop('disabled', false);
                      }
                  }
              };
              $('#auto_calc_application, #price').change(change);
          })();

          /* ---------------------------------------------------
          // initial setting for is_pre_acc and auto_calc_application
          -----------------------------------------------------*/
          (function(){
              if($('#is_pre_account').prop('checked')) {
                  $('#auto_calc_application, #pre_account_price').prop('disabled', false);;
              } else {
                  $('#auto_calc_application, #pre_account_price').prop('disabled', true);
                  $('#auto_calc_application').prop('checked', false);
                  $('#pre_account_price').val('');
              }
              if($('#auto_calc_application').prop('checked')) {
                  const price = $('#is_price').prop('checked') ? ($('#price').val() || 0) : 0;
                  const discountRate = $('#pre_account_discount_rate').val() || 0;
                  let accPrice = price * (discountRate/100);
                  $('#pre_account_price').val(accPrice);
                  $('#pre_account_price').prop('disabled', true);
              }
          })();
      })(jQuery);
  </script>
@stop

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap-datepicker.min.css') }}">
@endpush

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
            $('#reception_acceptance_day_end').datepicker({
                language:'ja',
                format: 'yyyy-mm-dd',
            });
        })(jQuery);
    </script>
@endpush
