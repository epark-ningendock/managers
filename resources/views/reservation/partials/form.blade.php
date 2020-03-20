@php
    use \App\Enums\Gender;
@endphp
<div class="form-entry" id="reservation">
<div class="box-body">

    <h2 class="section-title">受付情報</h2>

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


    <div class="form-group @if ($errors->has('course_id')) has-error @endif">
        <label for="course_id">検査コース<span class="form_required">必須</span></label>
        <select class="form-control w20em" name="course_id" id="course_id">
            <option></option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                        @if(old('course_id') == $course->id) selected @endif>{{ $course->name }}</option>
            @endforeach
        </select>
        @if ($errors->has('course_id')) <p class="help-block">{{ $errors->first('course_id') }}</p> @endif
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


    <div class="row form-group option-row option-container">
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
        <h2>受診日</h2>

        <!--<div class="col-md-3">
            <label for="reservation_date">受診日</label>
        </div>-->

        <div class="col-md-12">
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

    <div class="row form-group">

        <div class="col-md-3">
            <label for="internal_memo">医療機関備考</label>
        </div>

        <div class="col-md-9">
            <div class=" @if ($errors->has('internal_memo')) has-error @endif">
                <textarea class="form-control" name="internal_memo" id="internal_memo">{{ old('internal_memo') }}</textarea>
                @if ($errors->has('internal_memo')) <p class="help-block">{{ $errors->first('internal_memo') }}</p> @endif
            </div>
        </div>

    </div>

    <h2 class="section-title">受診者情報</h2>
    <div class="row mt-5">
        <div class="col-md-3">
            <p><a class="btn btn-primary" id="examinee-information" href="#">受診者検索</a></p>
        </div>
        <div class="col-md-9">
            <p class="color-gray" style="font-size: 1.2rem;">受診者名、受診者名かな、電話番号、診察券番号から検索が行えます。</p>
            <p class="color-gray" style="font-size: 1.2rem;">全ての顧客を表示する場合は何も入力せず検索ボタンを押下してください</p>
        </div>
    </div>
    <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">

    <div class="row mt-5">
        <div class="col-md-3">
            <label for="">お名前 <span class="form_required">必須</span></label>
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
            <label for="">お名前 かな<span class="form_required">必須</span> </label>
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
            <label for="tel">電話番号<span class="form_required">必須</span></label>
        </div>

        <div class="col-md-9">
            <div class="form-group @if ($errors->has('tel')) has-error @endif">
                <input type="text" class="form-control" name="tel" style="width: 90%;display: inline-block"
                       id="tel" value="{{ old('tel') }}"/>
                @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
            </div>
        </div>

    </div>

    <div class="form-group">
        <legend class="mb-0">{{ trans('messages.gender') }}</legend>
        @foreach(Gender::getValues() as $gender)
            <div class="radio">
                <input type="radio"
                       id="sex{{ $gender }}"
                       name="sex"
                       value="{{ $gender }}"
                        {{ old('sex', (isset($customer_detail) && isset($customer_detail->sex) ? $customer_detail->sex->value : Gender::MALE)) == $gender ? 'checked' : '' }}
                />

                <label for="sex{{ $gender }}" class="radio-label">{{ Gender::getDescription($gender) }}</label>
            </div>
        @endforeach
        @if ($errors->has('sex')) <p class="help-block has-error">{{ $errors->first('sex') }}</p> @endif
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group @if ($errors->has('birthday')) has-error @endif">
                <label for="birthday">{{ trans('messages.birthday') }}<span style="display: inline-block;text-indent: 15px;">yyyy-MM-dd形式で入力してください</span></label>
                <div class="input-group date datepicker mt-1"  data-date-format="yyyy-mm-dd" data-provide="datepicker">
                    <input type="text" class="form-control date-picker" name="birthday" id="birthday"
                           value="{{ old('birthday', ( isset($customer_detail) ? $customer_detail->birthday : '')) }}"/>
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
                @if ($errors->has('birthday')) <p class="help-block">{{ $errors->first('birthday') }}</p> @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <legend>{{ trans('messages.address') }}</legend>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group @if ($errors->has('postcode')) has-error @endif">
                <label for="postcode">{{ trans('messages.postcode') }}</label>
                <span class="p-country-name" style="display:none;">Japan</span>
                <input type="text" class="form-control" name="postcode1" id="postcode1"
                       value="{{ old('postcode1', ( isset($customer_detail) ? substr($customer_detail->postcode, 0, 3) : '')) }}" style="display: inline-block; width: 80px;"/> -
                <input type="text" class="form-control" name="postcode2" id="postcode2"
                       value="{{ old('postcode2', ( isset($customer_detail) ? substr($customer_detail->postcode, 3) : '')) }}" style="display: inline-block; width: 80px;"/>
                <input type="hidden" name="postcode" id="postcode" class="p-postal-code" size="8" maxlength="8" value="{{ old('postcode', (isset($customer_detail) ? $customer_detail->postcode : '')) }}" />
                <button type="button" class="btn btn-primary ml-4" id="postcode-search" style="margin-top:-4px;">郵便番号で住所を検索する</button>
                @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group @if ($errors->has('prefecture_id')) has-error @endif">
                <label for="prefecture_id">{{ trans('messages.prefectures') }}</label>
                <select name="prefecture_id" id="prefecture_id" class="form-control p-region-id">
                    <option value=""></option>
                    @foreach($prefectures as $prefecture)
                        <option value="{{ $prefecture->id }}"
                                @if(old('prefecture_id', (isset($customer_detail)? $customer_detail->prefecture_id : null)) == $prefecture->id )
                                selected="selected"
                                @endif
                        >{{ $prefecture->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('prefecture_id')) <p class="help-block">{{ $errors->first('prefecture_id') }}</p> @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group py-sm-1 @if ($errors->has('address1')) has-error @endif">
                @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
                <div class="form-group py-sm-1 @if ($errors->has('address1')) has-error @endif">
                    <label for="address1">{{ trans('messages.address1') }}
                    </label>
                    <input type="text" class="form-control p-locality" name="address1" id="address1"
                           value="{{ old('address1', ( isset($customer_detail) ? $customer_detail->address1 : '')) }}"/>
                    @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group py-sm-1 @if ($errors->has('address2')) has-error @endif">
                @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
                <label for="address2">{{ trans('messages.address2') }}
                </label>
                <input type="text" class="form-control p-street-address p-extended-address" name="address2" id="address2"
                       value="{{ old('address2', ( isset($customer_detail) ? $customer_detail->address2 : '')) }}"/>
                @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="form-group py-sm-1 @if ($errors->has('email')) has-error @endif">
                <label for="email">{{ trans('messages.email') }}
                </label>
                <input type="text" class="form-control" name="email" id="email"
                       value="{{ old('email', ( isset($customer_detail) ? $customer_detail->email : '')) }}"/>
                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group py-sm-1 @if ($errors->has('memo')) has-error @endif">
                <label for="memo">{{ trans('messages.memo') }}
                </label>
                <textarea class="form-control" name="memo" id="memo" cols="30" rows="5">{{ old('memo', ( isset($customer_detail) ? $customer_detail->memo : '')) }}</textarea>
                @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
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
        <a href="{{ url('/reservation') }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">受付</button>
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

            // $('#postcode-search').click(function(event){
            //     event.preventDefault();
            //     event.stopPropagation();
            //     $('#postcode').val(`${$('#postcode1').val()}${$('#postcode2').val()}`);
            //     //to trigger native keyup event
            //     $('.p-postal-code')[0].dispatchEvent(new KeyboardEvent('keyup', {'key': ''}));
            // });

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
                                        .append($(`<td>${courseOption.option.price.toLocaleString()}円</td>`))
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

@push('js')
    <script src="{{ asset('js/yubinbango.js') }}" charset="UTF-8"></script>
    <script>
        (function ($) {

            /* ---------------------------------------------------
             combine postcode before submit
            -----------------------------------------------------*/

            $('#postcode-search').click(function(event){
                event.preventDefault();
                event.stopPropagation();
                $('#postcode').val(`${$('#postcode1').val()}${$('#postcode2').val()}`);
                //to trigger native keyup event
                $('.p-postal-code')[0].dispatchEvent(new KeyboardEvent('keyup', {'key': ''}));
            });
        })(jQuery);
    </script>
@endpush

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

@includeIf('commons.std-modal-box')
