@php
    @endphp

<div class="box-body">
  {!! csrf_field() !!}

    <table class="responsive">
        <table class="table">

            <tr>
                <td>
                    <label for="course_id">検査コース</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('course_id')) has-error @endif">
                        <select class="form-control" name="course_id" id="course_id">
                            <option></option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                                        @if(old('course_id', isset($reservation) ? $reservation->course_id : null) == $course->id) selected @endif>{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('course_id')) <p class="help-block">{{ $errors->first('course_id') }}</p> @endif
                    </div>
                </td>

            </tr>
            <tr>

                <td>
                    <label for="regular_price">コース料金</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">

                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="コース料金"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label>オプション</label>
                </td>
                <td>
                    <div class="col-md-6 option-container">

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
                </td>
            </tr>

            <tr>
                <td>
                    <label>質問設定</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="adjustment_price">調整額</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('adjustment_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="adjustment_price" style="width: 90%;display: inline-block"
                                   id="adjustment_price" placeholder="調整額"
                                   value="{{ old('adjustment_price', isset($reservation) ? $reservation->adjustment_price : null) }}"/>
                            <span
                                    class="ml-2">円</span>
                        </div>
                        @if ($errors->has('adjustment_price')) <p class="help-block">{{ $errors->first('adjustment_price') }}</p> @endif
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="">合計金額</label>
                </td>

            </tr>

            <tr>
                <td>
                    <label>受診日</label>
                </td>
                <td>
                    <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd"
                         data-date-autoclose="true" data-date-language="ja">
                        <input type="text" class="form-control"
                               id="reservation_start_date" name="reservation_start_date"
                               placeholder="yyyy/mm/dd" value="{{ $reservation_start_date or '' }}">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="course_id">受付時間</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('course_id')) has-error @endif">
                        <select class="form-control" name="course_id" id="course_id">
                            <option></option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                                        @if(old('course_id', isset($reservation) ? $reservation->course_id : null) == $course->id) selected @endif>{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('course_id')) <p class="help-block">{{ $errors->first('course_id') }}</p> @endif
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="regular_price">受付・予約メモ</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                    <div style="width: 110%;">
                    <textarea type="text" class="form-control" name="regular_price" style="width: 90%;display: inline-block" id="regular_price" placeholder="受付・予約メモ"
                    value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/> </textarea><span class="ml-2">円</span>
                    </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <b>受診者情報</b>
                </th>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">受診者検索</label>
                </td>
                <td>
                    <a href="">受診者検索</a>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">お名前 姓</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="お名前 姓"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">お名前 名</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="お名前 名"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">お名前 かな 姓</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="お名前 かな 姓"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">お名前 かな 名</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="お名前 かな 名"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">電話番号</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="電話番号"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">診察券番号</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="診察券番号"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <b>受診者検索サブウィンドウ</b>
                </th>
            </tr>
            <tr>
                <td>
                    <label for="regular_price">検索フィールド</label>
                </td>
                <td>
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <div style="width: 110%;">
                            <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                                   id="regular_price" placeholder="検索フィールド"
                                   value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/>
                            <span class="ml-2">円</span>
                        </div>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
                </td>
            </tr>

                <tr>
                    <th>
                        <h3>顧客一覧</h3>
                    </th>
                </tr>
                <tr>
                    <th>顧客ID</th>
                    <th>名前</th>
                    <th>電話番号</th>
                    <th>診察券番号</th>
                </tr>
            @foreach($customers as $customer)

                <tr>
                    <td>{{ $customer-> id}}</td>
                    <td>{{ $customer->first_name }}</td>
                    <td>{{ $customer->tel }}</td>
                    <td>{{ $customer->registration_card_number }}</td>
                </tr>
            @endforeach
{{--            {{ $customer->links() }}--}}
            <tr>
                    <td>10002</td>
                    <td>木村次郎</td>
                    <td>0311114444</td>
                    <td>bb333dd11</td>
                </tr>




        </table>
    </table>
    

    <div class="box-footer">
        <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">作成</button>
    </div>
</div>


@include('commons.datepicker')
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
    .datepicker-days tbody tr {
        display: none;
    }

    .datepicker-days tbody tr:first-child {
        display: table-row;
    }
  </style>
@endpush
