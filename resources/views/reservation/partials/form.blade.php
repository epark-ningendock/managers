@php
    @endphp

<div class="box-body">
  {!! csrf_field() !!}

  <div class="col-md-4">
    <div class="form-group @if ($errors->has('course_id')) has-error @endif">
      <label for="course_id">検査コース</label>
      <select class="form-control" name="course_id" id="course_id">
        <option></option>
        @foreach($courses as $course)
          <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                  @if(old('course_id', isset($reservation) ? $reservation->course_id : null) == $course->id) selected @endif>{{ $course->name }}</option>
        @endforeach
      </select>
      @if ($errors->has('course_id')) <p class="help-block">{{ $errors->first('course_id') }}</p> @endif
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="col-md-4">
    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
      <label for="regular_price">コース料金</label>
      <div style="width: 110%;">
        <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
               id="regular_price" placeholder="コース料金"
               value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/> <span
            class="ml-2">円</span>
      </div>
      @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="col-md-6 option-container">
    <label>オプション</label>
    <table class="table table-borderless">
      <thead>
      <tr>
        <th>選択</th>
        <th>オプション</th>
        <th>価格</th>
      </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <div class="clearfix"></div>
  <div class="col-md-6 question-container">
    <label>質問設定</label>
    <div class="form-group ml-4">

    </div>
  </div>

  <div class="clearfix"></div>
  <div class="col-md-4">
    <div class="form-group @if ($errors->has('adjustment_price')) has-error @endif">
      <label for="adjustment_price">調整額</label>
      <div style="width: 110%;">
        <input type="number" class="form-control" name="adjustment_price" style="width: 90%;display: inline-block"
               id="adjustment_price" placeholder="調整額"
               value="{{ old('adjustment_price', isset($reservation) ? $reservation->adjustment_price : null) }}"/>
        <span
            class="ml-2">円</span>
      </div>
      @if ($errors->has('adjustment_price')) <p class="help-block">{{ $errors->first('adjustment_price') }}</p> @endif
    </div>
  </div>


  <div class="clearfix"></div>
  <div class="col-md-4">
    <label>合計金額</label>
    <span id="total" class="ml-2">0円</span>
  </div>

</div>
<div class="box-footer">
  <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
  <button type="submit" class="btn btn-primary">作成</button>
</div>

@section('script')
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // course options and questions
          // calculate total
          -----------------------------------------------------*/
          (function () {
              const calculateTotal = function() {
                  let coursePrice = parseInt($('#course_id option:selected').data('price') || 0);
                  let adjustmentPrice = parseInt($('#adjustment_price').val() || 0);
                  let total = adjustmentPrice + coursePrice;
                  $('.option:checked').each(function(idx, ele) {
                      total += parseInt($(ele).data('price'));
                  });
                  $('#total').html(total + '円');
              }

              const processUI = function () {
                  if ($('#course_id').val()) {
                      const url = '{{ route('course.detail.json', ':id') }}'.replace(':id', $('#course_id').val());
                      $.get(url, function (data) {
                          const tbody = $('.option-container tbody');
                          const questionGroup = $('.question-container .form-group');
                          
                          tbody.empty();
                          questionGroup.empty();

                          if (data && data.length > 0) {
                              const courseOptions = data[0].course_options;
                              const courseQuestions = data[0].course_questions;

                              $('.option-container').show();
                              courseOptions.forEach(function (courseOption) {
                                  $('<tr></tr>')
                                      .append($(`<td><input type="checkbox" class="checkbox option" data-price="${courseOption.option.price}" name="course_options"/></td>`))
                                      .append($(`<td>${courseOption.option.name}</td>`))
                                      .append($(`<td>${courseOption.option.price}円</td>`))
                                      .appendTo(tbody);
                              });


                              let flag = false;
                              courseQuestions.forEach(function (question) {
                                  if (question.question_title) {
                                      flag = true;
                                      questionGroup.append($(`<label>${question.question_title}</label>`))
                                      const answerGroup = $('<div class="answer-group"></div>').appendTo(questionGroup);
                                      for (let i = 1; i <= 10; i++) {
                                          let key = 'answer' + (i < 10 ? '0' : '') + i;
                                          if (question[key]) {
                                              answerGroup.append($(`<label><input type="checkbox" class="checkbox" name="questions_${question.id}[]"><span>${question[key]}</span></label>`))
                                          }
                                      }
                                  }
                              })
                              if (flag) {
                                  $('.question-container').show();
                              }
                          } else {
                              $('.option-container').hide();
                              $('.question-container').hide();
                          }
                          $('.option').change(calculateTotal);
                          calculateTotal();
                      });
                  }
              };
              $('#course_id').change(function () {
                  processUI();
              });
              $('.option-container').hide();
              $('.question-container').hide();
              processUI();

              $('.option').change(calculateTotal);
              $('#adjustment_price').keyup(calculateTotal);
              calculateTotal();
          })();

      })(jQuery);
  </script>
@stop

@push('css')
  <style>
    .option-container td, .option-container th {
      text-align: center;
    }

    .table-borderless tbody tr td, .table-borderless thead tr th {
      border: none;
    }

    .question-container label {
      display: block;
    }

    .question-container label span {
      margin-left: 5px;
    }

    .question-container input {
      display: inline-block;
    }

    .answer-group {
      padding-left: 10px;
    }
  </style>
@endpush