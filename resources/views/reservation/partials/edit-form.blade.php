<div class="form-entry" id="reservation">
<div class="box-body">
    <input type="hidden" name="lock_version" value="{{ $reservation->lock_version or '' }}" />
    <h2 class="section-title">受付情報</h2>

    <div class="row">
        <div class="col-md-3">
            <label for="is_health_insurance">健保</label>
        </div>

        <div class="col-md-9">
            <div class="form-group sm-form-group">
                <input type="checkbox" id="is_health_insurance" name="is_health_insurance" value="1" @if(old('is_health_insurance', $reservation->is_health_insurance) == '1') checked @endif />
                <label for="is_health_insurance">健保</label>
            </div>
        </div>

    </div>

    <div class="form-group @if ($errors->has('course_id')) has-error @endif">
        <label for="course_id">検査コース<span class="form_required">必須</span></label>
        <select class="w25em form-control" name="course_id" id="course_id">
            <option></option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                        @if(old('course_id', $reservation->course_id) == $course->id) selected @endif>{{ $course->name }}</option>
            @endforeach
        </select>
        @if ($errors->has('course_id')) <p class="help-block">{{ $errors->first('course_id') }}</p> @endif
    </div>

    <div class="form-group">
        <p class="form-inline">
        <label for="regular_price">コース料金</label>
        <span id="price">0円</span>
        </p>
    </div>

    <div class="row form-group option-row">
        <div class="box box-default option-container">
            <label id="checkbox">オプション</label>
            <table class="table table-bordered table-hover table-striped no-border">
                <thead>
                <tr>
                    <th style="text-align: left;">選択</th>
                    <th>オプション</th>
                    <th>価格</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row form-group">
        <div class="box box-default question-container">
            <label id="question-box">質問設定</label>
            <div class="form-group ml-4">
            </div>
        </div>
    </div>


    <!--<div class="row form-group">

        <div class="col-md-3">
            <label>質問設定</label>
        </div>

        <div class="col-md-9">
            <div class="question-container">
                <div class="form-group ml-4">
                </div>
            </div>
        </div>

    </div>-->


    <div class="row form-group">

        <div class="col-md-3">
            <label for="adjustment_price">調整額</label>
        </div>

        <div class="col-md-9">
            <div class="form-group sm-form-group @if ($errors->has('adjustment_price')) has-error @endif" style="margin-right: 21px;">
                <input type="number" class="form-control" name="adjustment_price" id="adjustment_price" placeholder="調整額"
                       value="{{ old('adjustment_price',  $reservation->adjustment_price) }}"/>
                <span class="ml-2" style="position: absolute;top: 0;right: -20px;">円</span>
                @if ($errors->has('adjustment_price')) <p class="help-block">{{ $errors->first('adjustment_price') }}</p> @endif
            </div>

        </div>

    </div>

    <div class="row form-group">

        <div class="col-md-3">
            <label for="">合計金額</label>
        </div>

        <div class="col-md-9">
            <span id="total" class="ml-2">0円</span>
        </div>

    </div>


    <div class="row form-group ">

        <div class="col-md-3">
            <label for="">決済ステータス</label>
        </div>

        <div class="col-md-9">
            <span class="ml-2">{{ $reservation->payment_status->description or '-' }}</span>
        </div>

    </div>

    <div class="row form-group no-field">

        <div class="col-md-3">
            <label for="">カード決済額</label>
        </div>

        <div class="col-md-9">
            <span class="ml-2">
                @if($reservation->settlement_price)
                    {{ number_format($reservation->settlement_price) }}
                 @else
                     -
                 @endif
                 円
            </span>
         </div>

     </div>


    <div class="row form-group">
         <div class="col-md-3">
             <label for="">キャッシュポ利用額</label>
         </div>

         <div class="col-md-9">
             <span class="ml-2">
                 @if($reservation->cashpo_used_price)
                     {{ number_format($reservation->cashpo_used_price) }}
                 @else
                     -
                 @endif
                 円
            </span>
        </div>

    </div>


    <div class="row form-group no-field">

        <div class="col-md-3">
            <label for="">現地支払額</label>
        </div>

        <div class="col-md-9">
            <span class="ml-2">
                {{ $reservation->is_payment == '0' ? '0円' : number_format($reservation->tax_included_price + $reservation->reservation_options()->get()->pluck('option_price')->sum() + $reservation->adjustment_price).'円' }}
            </span>
        </div>

    </div>




    <div class="row date-row-bar" style="display: none;" >
        <h2>受診日</h2>
        <!--<div class="col-md-3">
            <label for="reservation_date">受診日</label>
        </div>-->

        <div class="col-md-12">
            <div class="calendar-box" data-old="{{ old('reservation_date', $reservation->reservation_date->format('Y-m-d')) }}">

            </div>
            @if ($errors->has('reservation_date')) <p class="help-block text-danger" style="color: #ed5565;">{{ $errors->first('reservation_date') }}</p> @endif
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label>受付時間</label>
        </div>

        <div class="col-md-9">
            <div class="row" style="max-width: 300px;">
                <div class="col-md-5">
                    <select class="form-control" name="start_time_hour" id="start_time_hour">
                        <option value=""></option>
                        @for ( $x = 0; $x < 24; $x++)
                            <option
                                    value="{{ ( $x < 10 ) ? '0'.$x :  $x }}"
                                    @if ( old('start_time_hour', $reservation->start_time_hour) == (( $x < 10 ) ? '0'.$x :  $x))
                                        selected="selected"
                                    @endif

                            >{{ ( $x < 10 ) ? '0'.$x :  $x }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-sm-2 col-2"> : </div>
                <div class="col-md-5">
                    <select class="form-control" name="start_time_min" id="start_time_min">
                        <option value=""></option>
                        @for ( $x = 0; $x < 61; $x++)
                            <option
                                    value="{{ ( $x < 10 ) ? '0'.$x :  $x }}"
                                    @if ( old('start_time_min', $reservation->start_time_min) == (( $x < 10 ) ? '0'.$x :  $x))
                                        selected="selected"
                                    @endif
                            >{{ ( $x < 10 ) ? '0'.$x :  $x }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

    </div>

    <div class="row form-group">

        <div class="col-md-3">
            <label for="">第2希望日</label>
        </div>

        <div class="col-md-9">
            <span id="second_date" class="ml-2">{{ $reservation->second_date or '-' }}</span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">第3希望日</label>
        </div>

        <div class="col-md-9">
            <span id="third_date" class="ml-2">{{  $reservation->third_date or '-' }}</span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">受付番号</label>
        </div>

        <div class="col-md-9">
            <span id="acceptance_number" class="ml-2">{{ $reservation->acceptance_number or '-' }}</span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">受付形態</label>
        </div>

        <div class="col-md-9">
            <span id="terminal_type" class="ml-2">{{ $reservation->terminal_type->description or '-' }}</span>
        </div>

    </div>

    <div class="row form-group">

        <div class="col-md-3">
            <label for="reservation_memo">受付・予約メモ</label>
        </div>

        <div class="col-md-9">
            <div class=" @if ($errors->has('todays_memo')) has-error @endif">
                <textarea class="form-control" name="todays_memo" id="reservation_memo">{{ old('todays_memo', $reservation->todays_memo) }}</textarea>
                @if ($errors->has('todays_memo')) <p class="help-block">{{ $errors->first('todays_memo') }}</p> @endif
            </div>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="internal_memo">医療機関備考</label>
        </div>

        <div class="col-md-9">
            <div class=" @if ($errors->has('internal_memo')) has-error @endif">
                <textarea class="form-control" name="internal_memo" id="internal_memo">{{ old('internal_memo', $reservation->internal_memo) }}</textarea>
                @if ($errors->has('internal_memo')) <p class="help-block">{{ $errors->first('internal_memo') }}</p> @endif
            </div>
        </div>

    </div>

    @if ($reservation->cancellation_reason)
        <div class="row form-group">

            <div class="col-md-3">
                <label for="internal_memo">キャンセル理由</label>
            </div>

            <div class="col-md-9">
                <div class=" @if ($errors->has('cancellation_reason')) has-error @endif">
                    <textarea class="form-control" name="cancellation_reason" id="cancellation_reason">{{ old('cancellation_reason', $reservation->cancellation_reason) }}</textarea>
                    @if ($errors->has('cancellation_reason')) <p class="help-block">{{ $errors->first('cancellation_reason') }}</p> @endif
                </div>
            </div>

        </div>
    @endif


    <h2 class="section-title">申込者情報</h2>

    <div class="row form-group no-field">

        <div class="col-md-3">
            <label for="">お名前</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                <a class="detail-link" href="#" data-id="{{ $reservation->customer->id }}"
                    data-route="{{ route('customer.detail') }}">
                    {{ $reservation->applicant_name or '-' }}
                </a>
            </span>
        </div>

    </div>


    <div class="row form-group no-field">

        <div class="col-md-3">
            <label for="">お名前（かな）</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">{{ $reservation->applicant_name_kana or '-' }}</span>
        </div>

    </div>

    <div class="row form-group no-field">

        <div class="col-md-3">
            <label for="">電話番号</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">{{ $reservation->applicant_tel or '-' }}</span>
        </div>

    </div>


    <h2 class="section-title">受診者情報</h2>

    <div class="customer-info">
    <div class="row form-group">

        <div class="col-md-3">
            <label for="">お名前</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{  $reservation->customer->name }}
            </span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">電話番号</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{ $reservation->customer->tel or '-' }}
            </span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">登録形態</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{  $reservation->is_representative_desc or '-' }}
            </span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">診察券番号</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{  $reservation->customer->registration_card_number or '-' }}
            </span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">性別</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{ isset($reservation->customer->sex) ? $reservation->customer->sex->description : '-' }}
            </span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">生年月日</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{ $reservation->customer->birthday or '-' }}
            </span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">住所</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{$reservation->customer->address()}}
            </span>
        </div>

    </div>


    <div class="row form-group">

        <div class="col-md-3">
            <label for="">受診歴</label>
        </div>

        <div class="col-md-9">
            <span id="" class="ml-2">
                {{ $reservation->is_repeat_desc or '-' }}
            </span>
        </div>

    </div>
    </div>


    <div class="box-footer">
        <a href="{{ url('/reservation') }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">更新</button>
    </div>

</div>
</div>

@include('commons.datepicker')
@include('calendar.partials.horizontal-dateselector')
@section('script')
    <script>
        (function ($) {

            function checkNested(obj /*, level1, level2, ... levelN*/) {
                var args = Array.prototype.slice.call(arguments, 1);

                for (var i = 0; i < args.length; i++) {
                    if (!obj || !obj.hasOwnProperty(args[i])) {
                        return false;
                    }
                    obj = obj[args[i]];
                }
                return true;
            }


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
                    $('#total').html(total.toLocaleString() + '円');
                    $('#price').html(coursePrice.toLocaleString() + '円');
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

                                    let $courseOptionOldData = @json(old('course_options'), JSON_PRETTY_PRINT);

                                    if ( ! $courseOptionOldData ) {
                                        $courseOptionOldData = @json($course_options, JSON_PRETTY_PRINT);
                                    }

                                    let $courseOptionOldValue = ( $courseOptionOldData ) ? $courseOptionOldData : {};

                                    let checkedOldValue = ($courseOptionOldValue.hasOwnProperty(courseOption.option.id)  ) ? 'checked' : '';

                                    $('<tr></tr>')
                                        .append($('<td style="text-align:left; padding-left:15px;">
                                                     <input ${checkedOldValue} id ="option-${courseOption.option.id}" type="checkbox" class="checkbox option"
                                                        data-price="${courseOption.option.price}" name="course_options[${courseOption.option.id}]"
                                                        value="${courseOption.option.price}"/>
                                                     <label for="option-${courseOption.option.id}""></label>
                                                    </td>'))
                                        .append($('<td>${courseOption.option.name}</td>'))
                                        .append($('<td>${courseOption.option.price.toLocaleString()}円</td>'))
                                        .appendTo(tbody);
                                });


                                let flag = false;
                                courseQuestions.forEach(function (question) {
                                    if (question.question_title) {

                                        flag = true;

                                        questionGroup.append($(`<label>${question.question_title}</label><input type="hidden" name="course_question_ids[]" value="${question.id}" />`))
                                        const answerGroup = $('<div class="answer-group"></div>').appendTo(questionGroup);
                                        for (let i = 1; i <= 10; i++) {
                                            let key = 'answer' + (i < 10 ? '0' : '') + i;
                                            if (question[key]) {

                                                let input_name = `questions_${question.id}`;
                                                let $questionGroupOldData = @json(old(), JSON_PRETTY_PRINT);

                                                if ( ! $questionGroupOldData.length > 0) {
                                                    $questionGroupOldData = @json($questions, JSON_PRETTY_PRINT);
                                                }

                                                $questionGroupOldValue = ( $questionGroupOldData ) ? $questionGroupOldData : {};
                                                checkedOldValue = ( $questionGroupOldValue.hasOwnProperty(input_name) && ($questionGroupOldValue[input_name].hasOwnProperty(key))  ) ? 'checked' : '';

                                                answerGroup.append($(`<input ${checkedOldValue} type="checkbox" class="checkbox"
                                                                        id="questions_${question.id}[${key}]"
                                                                        name="questions_${question.id}[${key}]" value="${question[key]}">
                                                                      <label for="questions_${question.id}[${key}]">
                                                                        <span>${question[key]}</span>
                                                                      </label>`));
                                            }
                                        }
                                    }
                                });
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
                    } else {
                        $('.option-container-row, .question-container-row').hide();
                        $('.option-container tbody').empty();
                        $('.question-container .form-group').empty();
                        $('.option-container').hide();
                        $('.question-container').hide();
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
            width: fit-content;
        }

        .answer-group {
            padding-left: 15px;
            margin: 15px 0 30px;
        }

        .answer-group label {
            font-weight: normal;
            margin-bottom: 11px;
        }

        .answer-group label input {
            margin-right: 8px;
        }

        /* ---------------------------------------------------
        Daybox
        -----------------------------------------------------*/
        /*
        .date-row, .date-row.table td, .date-row.table th {
            border-color: #847f7f !important;
            border-width: 2px;
        }*/

        .date-row .daybox .txt {
            font-size: 11px;
            padding: 15px;
        }

        td.daybox.gray-background {
            background: #ddd;
        }

        td.daybox.red-background {
            background: #FCE4E4;
        }

        td.daybox.blue-background {
            background: #CBE0F8;
        }

        td.daybox.it-can-reserve  {
            cursor: pointer;
        }
        td.daybox.it-would-reserve {
            background: #fbfbbf;
        }
        .hide-tr {
            display: none;
        }
        .show-tr {
            display: table-row;
        }
        .prev-link {
            float: left;
        }
    </style>
@endpush

@includeIf('customer.partials.detail.detail-popup')
@includeIf('customer.partials.detail.detail-popup-script')
@includeIf('commons.std-modal-box')
