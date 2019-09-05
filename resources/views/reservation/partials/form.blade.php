<div class="box-body">
    
    <h3 class="section-title">受付情報</h3>
    <br/><br/>
    <div class="row">

        <div class="col-md-3">
            <label for="is_health_insurance">健保</label>
        </div>

        <div class="col-md-9">
            <div class="form-group sm-form-group">
                <input type="checkbox" id="is_health_insurance" name="is_health_insurance" value="1" @if(old('is_health_insurance') == '1') checked @endif />
                <label for="is_health_insurance">健保</label>
            </div>
        </div>

    </div>
    <div class="row">
    
        <div class="col-md-3">
            <label for="course_id">検査コース</label>
        </div>

        <div class="col-md-9">
            <div class="form-group sm-form-group @if ($errors->has('course_id')) has-error @endif">

                <select class="form-control" name="course_id" id="course_id">
                    <option></option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                                @if(old('course_id') == $course->id) selected @endif>{{ $course->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('course_id')) <p class="help-block">{{ $errors->first('course_id') }}</p> @endif
            </div>            
        </div>

    </div>


    <div class="row">
    
        <div class="col-md-3">
            <label for="regular_price">コース料金</label>
        </div>

        <div class="col-md-9">
            <div class="form-group sm-form-group">
                <span id="price">0円</span>
            </div>          
        </div>

    </div>


    <div class="row form-group option-container-row">
    
        <div class="col-md-3">
            <label id="checkbox option">オプション</label>
        </div>

        <div class="col-md-9">
            <div class="option-container" style="max-width: 550px;">
                <table class="table table-borderless">
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

    </div>   


    <div class="row form-group question-container-row">
    
        <div class="col-md-3">
            <label>質問設定</label>
        </div>

        <div class="col-md-9">
            <div class="question-container">
                <div class="form-group ml-4">
                </div>
            </div>  
        </div>

    </div>    


    <div class="row form-group">
    
        <div class="col-md-3">
            <label for="adjustment_price">調整額</label>
        </div>

        <div class="col-md-9">
            <div class="form-group sm-form-group @if ($errors->has('adjustment_price')) has-error @endif" style="margin-right: 21px;">
                    <input type="number" class="form-control" name="adjustment_price" id="adjustment_price" placeholder="調整額"
                           value="{{ old('adjustment_price') }}"/>
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
            <span id="total" class="ml-2"> 0円</span>
        </div>

    </div>


    <div class="row date-row-bar" style="display: none;">
    
        <div class="col-md-3">
            <label for="reservation_date">受診日</label>
        </div>

        <div class="col-md-9">
            <div class="calendar-box" data-old="{{ old('reservation_date') }}">
                
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
                                @if ( old('start_time_hour') == (( $x < 10 ) ? '0'.$x :  $x))
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
                                @if ( old('start_time_min') == (( $x < 10 ) ? '0'.$x :  $x))
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
            <label for="reservation_memo">受付・予約メモ</label>
        </div>

        <div class="col-md-9">
            <div class=" @if ($errors->has('reservation_memo')) has-error @endif">
                <textarea class="form-control" name="reservation_memo" id="reservation_memo">{{ old('reservation_memo') }}</textarea>
                @if ($errors->has('reservation_memo')) <p class="help-block">{{ $errors->first('reservation_memo') }}</p> @endif
            </div>
        </div>

    </div>   

    <h3 class="section-title">受診者情報</h3>
    <br/><a id="examinee-information" href="#">受診者検索</a>
    <br/><br/>

    <div class="row">

        <div class="col-md-3">
            <label for="">お名前 </label>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('family_name')) has-error @endif">
                        <span>姓</span>
                        <input type="text" class="form-control" name="family_name" style="width: 90%;display: inline-block"
                               id="family_name" value="{{ old('family_name') }}" />
                        @if ($errors->has('family_name')) <p class="help-block">{{ $errors->first('family_name') }}</p> @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                        <span>名</span>
                        <input type="text" class="form-control" name="first_name" style="width: 90%;display: inline-block"
                               id="first_name" value="{{ old('first_name') }}" />
                        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-3">
            <label for="">お名前 かな </label>
        </div>

        <div class="col-md-9">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('family_name_kana')) has-error @endif">
                        <span>せい</span>
                        <input type="text" class="form-control" name="family_name_kana" style="width: 90%;display: inline-block"
                               id="family_name_kana" value="{{ old('family_name_kana') }}" />
                        @if ($errors->has('family_name_kana')) <p class="help-block">{{ $errors->first('family_name_kana') }}</p> @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('first_name_kana')) has-error @endif">
                        <span>めい</span>
                        <input type="text" class="form-control" name="first_name_kana" style="width: 90%;display: inline-block"
                               id="first_name_kana" value="{{ old('first_name_kana') }}" />
                        @if ($errors->has('first_name_kana')) <p class="help-block">{{ $errors->first('first_name_kana') }}</p> @endif
                    </div>
                </div>
            </div>
        </div>

    </div> 


    <div class="row">

        <div class="col-md-3">
            <label for="tel">電話番号</label>
        </div>

        <div class="col-md-9">
            <div class="form-group @if ($errors->has('tel')) has-error @endif">
                <input type="text" class="form-control" name="tel" style="width: 90%;display: inline-block"
                       id="tel" value="{{ old('tel') }}"/>
                @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
            </div>
        </div>

    </div>     

    <div class="row">

        <div class="col-md-3">
            <label for="registration_card_number">診察券番号</label>
        </div>

        <div class="col-md-9">
            <div class="form-group @if ($errors->has('registration_card_number')) has-error @endif">
                <input type="text" class="form-control" name="registration_card_number" style="width: 90%;display: inline-block"
                       id="registration_card_number" value="{{ old('registration_card_number') }}" />
                @if ($errors->has('registration_card_number')) <p class="help-block">{{ $errors->first('registration_card_number') }}</p> @endif
            </div>
        </div>

    </div>


    <div class="box-footer">
        <a href="{{ url('/reception') }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">作成</button>
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
                    $('#total').html(total + '円');
                    $('#price').html(coursePrice + '円');
                }

                const processUI = function () {
                    if ($('#course_id').val()) {
                        const url = '{{ route('course.detail.json', ':id') }}'.replace(':id', $('#course_id').val());
                        $.get(url, function (data) {
                            $('.option-container-row, .question-container-row').show();
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

                                    let $courseOptionOldValue = ( $courseOptionOldData ) ? $courseOptionOldData : {};

                                    let checkedOldValue = ($courseOptionOldValue.hasOwnProperty(courseOption.option.id)  ) ? 'checked' : '';

                                    $('<tr></tr>')
                                        .append($(`<td style="text-align:left; padding-left:15px;">
                                                     <input ${checkedOldValue} id ="option-${courseOption.option.id}" type="checkbox" class="checkbox option"
                                                        data-price="${courseOption.option.price}" name="course_options[${courseOption.option.id}]"
                                                        value="${courseOption.option.price}"/>
                                                     <label for="option-${courseOption.option.id}""></label>
                                                    </td>`))
                                        .append($(`<td>${courseOption.option.name}</td>`))
                                        .append($(`<td>${courseOption.option.price}円</td>`))
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
                    } else {
                        $('.option-container-row, .question-container-row').hide();
                        $('.option-container tbody').empty();
                        $('.question-container .form-group').empty();
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
            width: fit-content;
        }

        .question-container label span {
            margin-left: 5px;
        }

        .question-container input {
            display: inline-block;
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
        .date-row, .date-row.table td, .date-row.table th {
            border-color: #847f7f !important;
            border-width: 2px;
        }

        .daybox .des-box {
            padding-top: 6px;
            border-top: 2px solid #847f7f;
            margin: 0 -8px;
            text-align: center;
        }

        .date-row .daybox .txt {
            font-size: 11px;
            padding: 15px;
        }
        .year-label th {
            background: transparent url({{ asset('img/calendar.png') }}) 10px 3px/30px no-repeat;
        }

        td.daybox.gray-background {
            background: #ddd;
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

@includeIf('commons.std-modal-box')
