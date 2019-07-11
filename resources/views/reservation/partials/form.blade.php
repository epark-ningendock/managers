@php
        @endphp

<div class="box-body">
    {!! csrf_field() !!}
    <table class="table">
        <tr>
            <td>
                <label for="course_id">検査コース</label>
            </td>
            <td>
                <div style="width: 90%;">
                    <div class="form-group @if ($errors->has('course_id')) has-error @endif">
                        {{-- <label for="course_id">検査コース</label> --}}
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
            </td>
        </tr>

        <tr>
            <td>
                <label for="regular_price">コース料金</label>
            </td>
            <td>
                <div style="width: 100%;">
                    <div class="form-group @if ($errors->has('regular_price')) has-error @endif">
                        <input type="number" class="form-control" name="regular_price" style="width: 90%;display: inline-block"
                               id="regular_price" placeholder="コース料金"
                               value="{{ old('regular_price', isset($reservation) ? $reservation->regular_price : null) }}"/> <span
                                class="ml-2">円</span>
                        @if ($errors->has('regular_price')) <p class="help-block">{{ $errors->first('regular_price') }}</p> @endif
                    </div>
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
            <td>
                <div class="col-md-6 question-container">
                    <div class="form-group ml-4">
                    </div>
            </td>
        </tr>

        <tr>
            <td>
                <label for="adjustment_price">調整額</label>
            </td>
            <td>
                <div class="form-group @if ($errors->has('adjustment_price')) has-error @endif">
                    <div style="width: 100%;">
                        <input type="number" class="form-control" name="adjustment_price" style="width: 90%;display: inline-block"
                               id="adjustment_price" placeholder="調整額"
                               value="{{ old('adjustment_price', isset($reservation) ? $reservation->adjustment_price : null) }}"/>
                        <span class="ml-2">円</span>
                    </div>
                    @if ($errors->has('adjustment_price')) <p class="help-block">{{ $errors->first('adjustment_price') }}</p> @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label for="">合計金額</label>
            </td>
            <td>
                <span class="ml-2"> 0円</span>
            </td>
        </tr>

        <tr>
            <td>
                <label for="">受診日</label>
            </td>

            <td>
                <div style="width: 90%;">
                    <div class="form-group @if ($errors->has('reservation_date')) has-error @endif">
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd"
                             data-date-autoclose="true" data-date-language="ja">
                            <input type="text" class="form-control"
                                   id="reservation_date" name="reservation_date"
                                   placeholder="yyyy/mm/dd" value="{{ $reservation_date or '' }}">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                        @if ($errors->has('reservation_date')) <p class="help-block">{{ $errors->first('reservation_date') }}</p> @endif
                    </div>
                </div>
            </td>
        </tr>


        <tr>
            <td></td>
            <td>
                <div style="width: 90%;">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">受付時間(時)</label>
                            <select class="form-control" name="start_time_hour" id="start_time_hour">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">受付時間(分)</label>
                            <select class="form-control" name="start_time_min" id="start_time_min">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <label for="reservation_memo">受付・予約メモ</label>
            </td>
            <td>
                <div style="width:100%">
                    <div class="form-group @if ($errors->has('reservation_memo')) has-error @endif">
                        <textarea name="reservation_memo" id="reservation_memo"  value="{{ old('reservation_memo', isset($reservation) ? $reservation->reservation_memo : null) }}"> </textarea>
                    </div>
                    @if ($errors->has('reservation_memo')) <p class="help-block">{{ $errors->first('reservation_memo') }}</p> @endif
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <h4>受診者情報</h4>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <table class="table">
                    <tr>
                        <td>
                            <h3>顧客一覧</h3>
                        </td>
                    </tr>
                    <tr>
                        <td>顧客ID</td>
                        <td>名前</td>
                        <td>電話番号</td>
                        <td>診察券番号</td>
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
                </table>
            </td>


        </tr>


        <tr>
            <td>
                <a href="">受診者検索</a>
            </td>

        </tr>

        <tr>
            <td>
                <label for="">お名前 </label>
            </td>
            <td>
                <div style="width: 96%;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if ($errors->has('family_name')) has-error @endif">
                                <span>姓</span>
                                <input type="number" class="form-control" name="family_name" style="width: 90%;display: inline-block"
                                       id="family_name" placeholder=""
                                       value="{{ old('family_name', isset($reservation) ? $reservation->family_name : null) }}"/>
                                @if ($errors->has('family_name')) <p class="help-block">{{ $errors->first('family_name') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                                <span>名</span>
                                <input type="number" class="form-control" name="first_name" style="width: 90%;display: inline-block"
                                       id="first_name" placeholder=""
                                       value="{{ old('first_name', isset($reservation) ? $reservation->first_name : null) }}"/>
                                @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <label for="">お名前 かな </label>
            </td>
            <td>

                <div style="width: 96%;">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group @if ($errors->has('family_name_kana')) has-error @endif">
                                <span>姓</span>
                                <input type="number" class="form-control" name="family_name_kana" style="width: 90%;display: inline-block"
                                       id="family_name_kana" placeholder=""
                                       value="{{ old('family_name_kana', isset($reservation) ? $reservation->family_name_kana : null) }}"/>
                                @if ($errors->has('family_name_kana')) <p class="help-block">{{ $errors->first('family_name_kana') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group @if ($errors->has('first_name_kana')) has-error @endif">
                                <span>名</span>
                                <input type="number" class="form-control" name="first_name_kana" style="width: 90%;display: inline-block"
                                       id="first_name_kana" placeholder=""
                                       value="{{ old('first_name_kana', isset($reservation) ? $reservation->first_name_kana : null) }}"/>
                                @if ($errors->has('first_name_kana')) <p class="help-block">{{ $errors->first('first_name_kana') }}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <label for="tel">電話番号</label>
            </td>
            <td>
                <div style="width: 100%;">
                    <div class="form-group @if ($errors->has('tel')) has-error @endif">
                        <span>名</span>
                        <input type="number" class="form-control" name="tel" style="width: 90%;display: inline-block"
                               id="tel" placeholder=""
                               value="{{ old('tel', isset($reservation) ? $reservation->tel : null) }}"/>
                        @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <label for="registration_card_number">診察券番号</label>
            </td>
            <td>
                <div style="width: 100%;">
                    <div class="form-group @if ($errors->has('registration_card_number')) has-error @endif">
                        <span>名</span>
                        <input type="number" class="form-control" name="registration_card_number" style="width: 90%;display: inline-block"
                               id="registration_card_number" placeholder=""
                               value="{{ old('registration_card_number', isset($reservation) ? $reservation->registration_card_number : null) }}"/>
                        @if ($errors->has('registration_card_number')) <p class="help-block">{{ $errors->first('registration_card_number') }}</p> @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

</div>
<div class="box-footer">
    <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
    <button type="submit" class="btn btn-primary">作成</button>
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
    </style>
@endpush