@php
  use App\Enums\Authority;
  use \App\Enums\Permission;

  if(isset($course)) {
    $course_details = $course->course_details;
    $course_options = $course->course_options;
    $course_images = $course->course_images;
    $course_questions = $course->course_questions;
  }

  $c_images = collect(old('course_images', []));
  $c_image_orders = collect(old('course_image_orders', []));
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
             value="{{ old('name', (isset($course) ? $course->name : null)) }}"
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
      <select name="calendar_id" id="calendar_id" class="form-control" >
        <option value="">なし</option>
        @foreach ($calendars as $calendar)
          <option {{ old('calendar_id', isset($course) ? $course->calendar_id : null) == $calendar->id ? 'selected' : '' }}
                  value="{{ $calendar->id }}"> {{ $calendar->name }}</option>
        @endforeach
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

    <div class="form-group">
      <label>画像の選択	</label>
      <div class="row">
        @foreach($images as $index => $image)
          @php
            $is_checked = false;
            $order_value = 0;
            if($c_images->isNotEmpty()) {
              $order_value = $c_image_orders[$index];
              $is_checked = $order_value != '';
            } else if(isset($course_images)) {
              $temp = $course_images->where('hospital_image_id', $image->id)->flatten(1);
              if ($temp->isNotEmpty()) {
                $is_checked = true;
                $order_value = $temp[0]->image_order_id;
              }
            }
          @endphp
          <div class="col-xs-4 mb-3">
            <div>
              <input type="checkbox" class="checkbox d-inline-block image-checkbox" name="course_images[]"
                     {{ $is_checked ? 'checked' : '' }}
                     id="course_images_{{$image->id}}" value="{{$image->id}}" />
              <label for="course_images_{{$image->id}}">{{ $image->name }}.{{ $image->extension }}</label>
            </div>
            <div class="mb-3 text-center">
              <image src="{{ $image->path }}" style="width: 40px; height: 40px;"></image>
            </div>
            <select name="course_image_orders[]" class="form-control">
              @foreach($image_orders as $image_order)
                <option value="{{ $image_order->id }}"
                    {{ $order_value == $image_order->id ? 'selected' : '' }} >
                  {{ $image_order->name }}
                </option>
`             @endforeach
            </select>
          </div>
        @endforeach
      </div>
    </div>

    <div class="form-group">
      <label for="course_point">コースの特徴</label>
      <textarea class="form-control" id="course_point" name="course_point" rows="5">
        {{ old('course_point', (isset($course) ? $course->course_point : null)) }}
      </textarea>
      <span class="pull-right">0/1000文字</span>
    </div>

    <div class="form-group">
      <label for="course_notice">注意事項</label>
      <textarea class="form-control" id="course_notice" name="course_notice">
        {{ old('course_notice', (isset($course) ? $course->course_notice : null)) }}
      </textarea>
      <span class="pull-right">0/1000文字</span>
    </div>

    <div class="form-group">
      <label for="course_cancel">キャンセルについて</label>
      <textarea class="form-control" id="course_cancel" name="course_cancel">
        {{ old('course_cancel', (isset($course) ? $course->course_cancel : null)) }}
      </textarea>
      <span class="pull-right">0/1000文字</span>
    </div>

    <div class="form-group">
      <label>受付時間 <span class="text-red">必須</span></label>
      <div class="form-horizontal">
          本日から
          <div class="d-inline-block @if ($errors->has('reception_start_day')) has-error @endif" >
              <input type="number" id="reception_start_day" name="reception_start_day" class="form-control d-inline-block ml-2" style="width:60px;"
                     value="{{ old('reception_start_day', (isset($course) ? $course->reception_start_date%1000 : 6)) }}" />
          </div>
          ヶ月
          <div class="d-inline-block @if ($errors->has('reception_start_month')) has-error @endif" >
              <input type="number" id="reception_start_month" name="reception_start_month" class="form-control d-inline-block ml-2 mr-2" style="width:60px;"
                     value="{{ old('reception_start_month', (isset($course) ? intdiv($course->reception_start_date, 1000) : 0)) }}" />
          </div>
          日間、受付可能。 うち
          <div class="d-inline-block @if ($errors->has('reception_end_day')) has-error @endif" >
              <input type="number" id="reception_end_day" name="reception_end_day" class="form-control d-inline-block ml-2 mr-2" style="width:60px;"
                     value="{{ old('reception_end_day', (isset($course) ? $course->reception_end_date % 1000 : 0)) }}" />
          </div>
          ヶ月
          <div class="d-inline-block @if ($errors->has('reception_end_month')) has-error @endif" >
              <input type="number" id="reception_end_month" name="reception_end_month" class="form-control d-inline-block ml-2" style="width:60px;"
                     value="{{ old('reception_end_month', (isset($course) ? intdiv($course->reception_end_date, 1000) : 7)) }}" />
          </div>
          日間から受付開始。
      </div>
      <div class="mt-2">(事前決済のみ利用の場合、受付期限は90日となります。）</div>
      @if ($errors->has('reception_start_day')) <p class="help-block text-red">{{ $errors->first('reception_start_day') }}</p>@endif
      @if ($errors->has('reception_start_month')) <p class="help-block text-red">{{ $errors->first('reception_start_month') }}</p> @endif
      @if ($errors->has('reception_end_day')) <p class="help-block text-red">{{ $errors->first('reception_end_day') }}</p> @endif
      @if ($errors->has('reception_end_month')) <p class="help-block text-red">{{ $errors->first('reception_end_month') }}</p> @endif
    </div>
    <div class="form-group">
      <label>受付許可日  <span class="text-red">必須</span></label>
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
    </div>
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
    <div class="form-group @if ($errors->has('price')) has-error @endif">
      <label for="name">表示価格</label>
      <div>
        <input type="checkbox" class="checkbox d-inline-block mr-2" name="is_price" value="1"
               id="is_price" {{ old('is_price', (isset($course)? $course->is_price : null)) == 1 ? 'checked' : '' }} />
        <label for="is_price">価格</label>
        <input type="number" class="form-control d-inline-block mr-2 ml-2" id="price" name="price" style="width: 100px;"
               value="{{ old('price', (isset($course) ? $course->price : null)) }}">
        円
        <span class="ml-5">０円（税込）</span>
      </div>
      @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
    </div>

    <div class="form-group @if ( $errors->has('price_memo')) has-error @endif">
      <label for="name">手動設定金額</label>
      <div>
        <input type="checkbox" class="checkbox d-inline-block mr-2" name="is_price_memo" value="1"
               id="is_price_memo" {{ old('is_price_memo', (isset($course)? $course->is_price_memo : null)) == 1 ? 'checked' : '' }} />
        <label for="is_price_memo">メモ</label>
        <input type="text" class="form-control d-inline-block mr-2 ml-2" id="price_memo" name="price_memo" style="width: 230px;"
               value="{{ old('price_memo', (isset($course) ? $course->price_memo : null)) }}">
      </div>
      @if ($errors->has('price_memo')) <p class="help-block">{{ $errors->first('price_memo') }}</p> @endif
    </div>
    <div class="separator mb-3"></div>
    <div class="form-group" >
      <label for="tax_class">税区分<span class="text-red 必須"></span></label>
      <div class="row">
        <div class="col-md-12">
          <select name="tax_class" id="tax_class" class="form-control">
            @foreach ($tax_classes as $tax_class)
              <option {{ old('tax_class', isset($course) ? $course->$tax_class : null) == $tax_class->id ? 'selected' : '' }}
              value="{{ $tax_class->id }}"> {{ $tax_class->name }}</option>
            @endforeach
          </select>
        </div>
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
  <div class="box-body">
    <h4 class="d-inline-block">価格</h4></span>
    <div class="form-group">
      <label for="name">事前決済価格</label>
      <div>０円（税込）</div>
    </div>
    <div class="separator mb-3"></div>
    <div class="form-group @if ($errors->has('is_pre_account')) has-error @endif" >
      <label for="tax_class">利用設定 <span class="text-red">必須</span></label>
      <div>
        <input type="radio" class="checkbox d-inline-block" id="is_pre_account_normal_payment"
               {{ old('is_pre_account', isset($course) ? $course->is_pre_account : null) == 0 ? 'checked' : '' }}
               name="is_pre_account" value="0"/>
        <label for="is_pre_account_normal_payment">通常決済利用</label>
        <input type="radio" class="checkbox d-inline-block ml-2" name="is_pre_account" id="is_pre_account_pre_payment"
               {{ old('is_pre_account', isset($course) ? $course->is_pre_account : null) == 1 ? 'checked' : '' }}
               value="1"/>
        <label for="is_pre_account_pre_payment">事前決済利用</label>

      </div>
      @if ($errors->has('is_pre_account')) <p class="help-block">{{ $errors->first('is_pre_account') }}</p> @endif
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
  <div class="box-body">
    <h4 class="d-inline-block">オプションの内容</h4></span>
    <table class="table table-bordered">
      <tr>
        <td class="text-center">選択</td>
        <td class="text-center">オプション名</td>
        <td class="text-center">価格</td>
      </tr>
      @foreach($options as $option)
        @php
          $is_checked = false;
          if ($o_option_ids->isNotEmpty()) {
            $is_checked = $o_option_ids->contains($option->id);
          } else if(isset($course_options)) {
            $is_checked = $course_options->where('option_id', $option->id)->isNotEmpty();
          }
        @endphp
        <tr>
          <td style="width: 60px;text-align: center;">
            <input type="checkbox" name="option_ids[]" value="{{ $option->id }}" {{ $is_checked ? 'checked' : '' }}/>
          </td>
          <td class="text-center">{{ $option->name }}</td>
          <td class="text-center">{{ $option->price }} 円</td>
        </tr>
      @endforeach
    </table>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="box-tools" data-widget="collapse">
      <button type="button" class="btn btn-sm">
        <i class="fa fa-minus"></i></button>
    </div>
    <h1 class="box-title">設定項目</h1>
    <di></dI>
  </div>
  <div class="box-body">
    <table class="table table-bordered">
      @foreach($majors as $major)
        @foreach($major->middle_classifications as $middle)
        <tr>
          @if(!isset($last) || $major != $last)
            <td colspan="{{ count($major->middle_classifications) }}">{{ $major->name }}</td>
            @php
              $last = $major
            @endphp
          @endif
          <td>{{ $middle->name }}</td>
          <td>
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
                    $minor_value = $minor->is_fregist == '1' ? $temp[0]->select_status : $temp[0]->inputstring;
                  }
                }
              @endphp
              <input type="hidden" name="minor_ids[]" value="{{ $minor->id }}" />
              @if($minor->is_fregist == '1')
                <input type="checkbox" class="checkbox d-inline-block minor-checkbox" name="minor_values[]"
                       id="{{ 'minor_id_'.$minor->id }}"
                       {{ $minor_value == 1 ? 'checked' : '' }} value="{{ $minor->id }}" />
                <label class="mr-2" for="{{ 'minor_id_'.$minor->id }}">{{ $minor->name }}</label>
              @else
                <input type="text" name="minor_values[]"
                       class="form-control minor-text @if ($index > 0) mt-2 @endif" data-maxlength="{{ $minor->max_length }}"
                  value = "{{ $minor_value }}" />
                <span class="pull-right">0/{{ $minor->max_length }}文字</span>
              @endif
            @endforeach
          </td>
        </tr>
        @endforeach
      @endforeach
    </table>
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
    <div class="box-body">
      <div class="form-group">
        <label for="name">質問事項の利用</label>
        <div>
          <input type="radio" class="checkbox d-inline-block mr-2 is_question" {{ $is_question == 1 ? 'checked' : '' }}
                 id="is_question_use_{{$qi}}" name="is_question_{{ $qi }}" value="1"/>
          <label for="is_question_use_{{$qi}}">利用する</label>
          <input type="radio" class="checkbox d-inline-block mr-2 ml-2 is_question" {{ $is_question == 0 ? 'checked' : '' }}
                 id="is_question_not_use_{{$qi}}" name="is_question_{{ $qi }}" value="0"/>
          <label for="is_question_not_use_{{$qi}}">利用しない</label>
            <input type="hidden" value="{{ $is_question }}" name="is_questions[]"/>
        </div>
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
                  if (len > 1000) {
                      $(this).val($(this).val().substring(0, 999));
                  } else {
                      $(this).next('span').text(len + '/1000文字');
                  }
              });

              $('.minor-text').on('keyup', function() {
                  const max = parseInt($(this).data('maxlength'));
                  const len = $(this).val().length;
                  if (len > max) {
                      $(this).val($(this).val().substring(0, max));
                  } else {
                      $(this).next('span').text(len + '/' + max + '文字');
                  }
              });
          })();

          /* ---------------------------------------------------
          // image order enable/disable
          -----------------------------------------------------*/
          (function () {
              const change = function(ele) {
                  const orderEle = ele.parent().parent().find('select');
                  if (ele.prop('checked')) {
                      orderEle.prop('disabled', false);
                      ele.siblings('input:hidden').remove();
                      orderEle.next('input:hidden').remove();
                  } else {
                      $('<input type="hidden" name="course_images[]" />').val('0').appendTo(ele.parent());
                      $('<input type="hidden" name="course_image_orders[]" value=""/>').insertAfter(orderEle);
                      orderEle.prop('disabled', true);
                  }
              };

              $('.image-checkbox').each(function(index, ele) {
                  ele = $(ele);
                  ele.change(function() {
                      change(ele);
                  });
                  change(ele);
              });
          })();

          /* ---------------------------------------------------
          // minor checkbox values
          -----------------------------------------------------*/
          (function () {
              const change = function(ele) {
                  if (ele.prop('checked')) {
                      ele.next('input:hidden').remove();
                  } else {
                      $('<input type="hidden" name="minor_values[]" value="0"/>').insertAfter(ele);
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


      })(jQuery);
  </script>
@stop