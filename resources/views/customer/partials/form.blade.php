@php
    use \App\Enums\Gender;
@endphp
<div class="table-responsive">

    <table class="table table-bordered">
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.customer_id') }}</td>
            <td> {{ $customer_detail->id or '' }} </td>
            <td class="gray-cell-bg">{{ trans('messages.consultation_ticket_number') }}</td>
            <td>
                <div class="form-group @if ($errors->has('registration_card_number')) has-error @endif">
                    <input type="text" class="form-control" name="registration_card_number" id="registration_card_number"
                           value="{{ old('registration_card_number', ( isset($customer_detail) ? $customer_detail->registration_card_number : '')) }}"/>
                    @if ($errors->has('registration_card_number')) <p class="help-block">{{ $errors->first('registration_card_number') }}</p> @endif
                </div>
            </td>
        </tr>
        <tr>
            <td  class="gray-cell-bg"><label for="name">{{ trans('messages.name') }}<span class="text-danger">(*)</span></label></td>
            <td>

                <div class="form-group @if ($errors->has('family_name')) has-error @endif">
                    <input type="text" class="form-control" name="family_name" id="family_name"
                           value="{{ old('family_name', ( isset($customer_detail) ? $customer_detail->family_name : '')) }}"/>
                    @if ($errors->has('family_name')) <p class="help-block">{{ $errors->first('family_name') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                    <input type="text" class="form-control" name="first_name" id="first_name"
                           value="{{ old('first_name', ( isset($customer_detail) ? $customer_detail->first_name : '')) }}"/>
                    @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                </div>

            </td>
            <td class="gray-cell-bg"><label for="name_kana">{{ trans('messages.name_kana') }}</label></td>
            <td>

                <div class="form-group @if ($errors->has('family_name_kana')) has-error @endif">
                    <input type="text" class="form-control" name="family_name_kana" id="family_name_kana"
                           value="{{ old('family_name_kana', ( isset($customer_detail) ? $customer_detail->family_name_kana : '')) }}"/>
                    @if ($errors->has('family_name_kana')) <p class="help-block">{{ $errors->first('family_name_kana') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('first_name_kana')) has-error @endif">
                    <input type="text" class="form-control" name="first_name_kana" id="first_name_kana"
                           value="{{ old('first_name_kana', ( isset($customer_detail) ? $customer_detail->first_name_kana : '')) }}"/>
                    @if ($errors->has('first_name_kana')) <p class="help-block">{{ $errors->first('first_name_kana') }}</p> @endif
                </div>

            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg"><label for="tel">{{ trans('messages.tel') }}</label></td>
            <td colspan="3">

                <p>
                    ハイフンなどを付けずに数字だけを入力します（例）031234 ****
                </p>

                <div class="form-group @if ($errors->has('tel')) has-error @endif">
                    <input type="text" class="form-control" name="tel" id="tel"
                           value="{{ old('tel', ( isset($customer_detail) ? $customer_detail->tel : '')) }}"/>
                    @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
                </div>

            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg"><label for="gender">{{ trans('messages.gender') }}</label></td>
            <td class="text-left">
                <div class="form-group @if ($errors->has('sex')) has-error @endif">
                    <label for="sex">状態</label>
                    <div class="radio">
                        @foreach(Gender::getValues() as $gender)
                            <label>
                                <input type="radio" name="sex"
                                       value="{{ $gender }}"
                                       @if ( old('sex', (isset($customer_detail) && $customer_detail->sex->value)) == $gender)
                                       checked="checked"
                                    @endif
                                />
                                {{ Gender::getDescription($gender) }}
                            </label>
                        @endforeach
                    </div>
                    @if ($errors->has('sex')) <p class="help-block">{{ $errors->first('sex') }}</p> @endif
                </div>
            </td>
            <td class="gray-cell-bg">{{ trans('messages.birthday') }}</td>
            <td>

                <div class="form-group @if ($errors->has('birthday')) has-error @endif">
                    <div class="input-group date datepicker"  data-date-format="yyyy-mm-dd" data-provide="datepicker">
                        <input type="text" class="form-control date-picker" name="birthday" id="birthday"
                               value="{{ old('birthday', ( isset($customer_detail) ? $customer_detail->birthday : '')) }}"/>
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                    @if ($errors->has('birthday')) <p class="help-block">{{ $errors->first('birthday') }}</p> @endif
                </div>

            </td>
        </tr>

        <tr>
            <td class="gray-cell-bg">{{ trans('messages.postcode') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('postcode')) has-error @endif">
                    <span class="p-country-name" style="display:none;">Japan</span>
                    <input type="text" class="form-control" name="postcode1" id="postcode1"
                           value="{{ old('postcode1', ( isset($customer_detail) ? substr($customer_detail->postcode, 0, 3) : '')) }}" style="display: inline-block; width: 80px;"/> -
                    <input type="text" class="form-control" name="postcode2" id="postcode2"
                           value="{{ old('postcode2', ( isset($customer_detail) ? substr($customer_detail->postcode, 3) : '')) }}" style="display: inline-block; width: 80px;"/>
                    <input type="hidden" name="postcode" id="postcode" class="p-postal-code" size="8" maxlength="8" value="{{ old('postcode', (isset($customer_detail) ? $customer_detail->postcode : '')) }}" />
                    <button type="button" class="btn btn-primary ml-4" id="postcode-search" style="margin-top:-4px;">郵便番号で住所を検索する</button>
                    <input type="text" id="region" class="p-region" style="display: none">
                    @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
                </div>

            </td>
        </tr>

        <tr>
            <td class="gray-cell-bg">{{ trans('messages.prefectures') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('prefecture_id')) has-error @endif">
                    <select name="prefecture_id" id="prefecture_id" class="form-control">
                        <option value=""></option>
                        @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->id }}"
                                @if(isset($customer_detail) && $prefecture->id === $customer_detail->prefecture_id )
                                    selected="selected"
                                    @endif
                            >{{ $prefecture->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('prefecture_id')) <p class="help-block">{{ $errors->first('prefecture_id') }}</p> @endif
                </div>
            </td>
        </tr>


        <tr>
            <td class="gray-cell-bg">{{ trans('messages.address1') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('address1')) has-error @endif">
                    <input type="text" class="form-control p-street-address p-extended-address" name="address1" id="address1"
                           value="{{ old('address1', ( isset($customer_detail) ? $customer_detail->address1 : '')) }}"/>
                    @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
                </div>
            </td>
        </tr>

        <tr>
            <td class="gray-cell-bg">{{ trans('messages.address2') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('address2')) has-error @endif">
                    <input type="text" class="form-control" name="address2" id="address2"
                           value="{{ old('address2', ( isset($customer_detail) ? $customer_detail->address2 : '')) }}"/>
                    @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
                </div>
            </td>
        </tr>



        <tr>
            <td class="gray-cell-bg">{{ trans('messages.email') }}</td>
            <td colspan="3">

                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <input type="text" class="form-control" name="email" id="email"
                           value="{{ old('email', ( isset($customer_detail) ? $customer_detail->email : '')) }}"/>
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>

            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.memo') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('memo')) has-error @endif">
                    <input type="text" class="form-control" name="memo" id="memo"
                           value="{{ old('memo', ( (isset($customer_detail) && !empty($customer_detail->memo)) ? $customer_detail->memo : '')) }}"/>
                    @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
                </div>
            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.claim_count') }}<span class="text-danger">(*)</span></td>
            <td>
                <div class="form-group @if ($errors->has('claim_count')) has-error @endif">
                    <input type="text" class="form-control" name="claim_count" id="claim_count"
                           value="{{ old('claim_count', ( isset($customer_detail) ? $customer_detail->claim_count : '')) }}"/>
                    @if ($errors->has('claim_count')) <p class="help-block">{{ $errors->first('claim_count') }}</p> @endif
                </div>
            </td>
            <td class="gray-cell-bg">{{ trans('messages.recall_count') }}<span class="text-danger">(*)</span></td>
            <td>
                <div class="form-group @if ($errors->has('recall_count')) has-error @endif">
                    <input type="text" class="form-control" name="recall_count" id="recall_count"
                           value="{{ old('recall_count', ( isset($customer_detail) ? $customer_detail->recall_count : '')) }}"/>
                    @if ($errors->has('recall_count')) <p class="help-block">{{ $errors->first('recall_count') }}</p> @endif
                </div>
            </td>
        </tr>

    </table>

</div>

@includeIf('commons.datepicker')

@push('js')
    <script src="{{ asset('js/yubinbango.js') }}" charset="UTF-8"></script>
    <script>
        (function ($) {

            /* ---------------------------------------------------
             combine postcode before submit
            -----------------------------------------------------*/
            $('form').submit(function () {
                $('#postcode').val(`${$('#postcode1').val()}${$('#postcode2').val()}`);
            });

            $('#postcode-search').click(function(event){
                event.preventDefault();
                event.stopPropagation();
                $('#postcode').val(`${$('#postcode1').val()}${$('#postcode2').val()}`);
                //to trigger native keyup event
                $('.p-postal-code')[0].dispatchEvent(new KeyboardEvent('keyup', {'key': ''}));
            });

            $('#region').change(function() {
                const region = $('#region').val();
                if (region) {
                    $('#prefecture_id option').each(function(i, o){
                       if (region == $(o).html()) {
                           $(o).prop('selected', true);
                       }
                    });
                }
            });

        })(jQuery);
    </script>
@endpush
@push('css')
    <style>
        p.help-block {
            text-align: left;
        }
    </style>
@endpush