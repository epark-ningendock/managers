@php
    use \App\Enums\Gender;
@endphp
<div class="form-entry">
    <div class="box-body staff-form">
        <h2>顧客管理</h2>
        <div class="row">
            @if (isset($customer_detail->id))
            <div class="col-md-6">
                <label for="name">{{ trans('messages.customer_id') }}
                </label>
                <p>{{ $customer_detail->id or '' }}</p>
            </div>
            @endif
            <div class="col-md-6">
                <div class="form-group py-sm-1 @if ($errors->has('registration_card_number')) has-error @endif">
                    <label for="name">{{ trans('messages.consultation_ticket_number') }}</label>
                    <input type="text" class="form-control" name="registration_card_number" id="registration_card_number"
                           value="{{ old('registration_card_number', ( isset($customer_detail) ? $customer_detail->registration_card_number : '')) }}"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <legend class="mt-0">{{ trans('messages.name') }}</legend>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group py-sm-1 @if ($errors->has('family_name')) has-error @endif">
                    <label for="name">姓<span class="form_required">必須</span>
                    </label>
                    <input type="text" class="form-control" name="family_name" id="family_name"
                           value="{{ old('family_name', ( isset($customer_detail) ? $customer_detail->family_name : '')) }}"/>
                    @if ($errors->has('family_name')) <p class="help-block">{{ $errors->first('family_name') }}</p> @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group py-sm-1 @if ($errors->has('first_name')) has-error @endif">
                    <label for="name">名<span class="form_required">必須</span></label>
                    <input type="text" class="form-control" name="first_name" id="first_name"
                           value="{{ old('first_name', ( isset($customer_detail) ? $customer_detail->first_name : '')) }}"/>
                    @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <legend>{{ trans('messages.name_kana') }}</legend>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group py-sm-1 @if ($errors->has('family_name_kana')) has-error @endif">
                    <label for="name">せい
                    </label>
                    <input type="text" class="form-control" name="family_name_kana" id="family_name_kana"
                           value="{{ old('family_name_kana', ( isset($customer_detail) ? $customer_detail->family_name_kana : '')) }}"/>
                    @if ($errors->has('family_name_kana')) <p class="help-block">{{ $errors->first('family_name_kana') }}</p> @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group py-sm-1 @if ($errors->has('first_name_kana')) has-error @endif">
                    <label for="name">めい</label>
                    <input type="text" class="form-control" name="first_name_kana" id="first_name"
                           value="{{ old('first_name_kana', ( isset($customer_detail) ? $customer_detail->first_name_kana : '')) }}"/>
                    @if ($errors->has('first_name_kana')) <p class="help-block">{{ $errors->first('first_name_kana') }}</p> @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group py-sm-1 @if ($errors->has('tel')) has-error @endif">
                    <label for="tel">{{ trans('messages.tel') }}<span style="display: inline-block;text-indent: 15px;">ハイフンなどを付けずに数字だけを入力します（例）031234 ****</span>
                    </label>
                    <input type="text" class="form-control" name="tel" id="tel"
                           value="{{ old('tel', ( isset($customer_detail) ? $customer_detail->tel : '')) }}"/>
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
                           {{ old('sex', (isset($customer_detail) ? $customer_detail->sex->value : $gender)) == $gender ? 'checked' : '' }}
                    />

                    <label for="sex{{ $gender }}" class="radio-label">{{ Gender::getDescription($gender) }}</label>
                </div>
            @endforeach
            @if ($errors->has('sex')) <p class="help-block has-error">{{ $errors->first('sex') }}</p> @endif
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="form-group @if ($errors->has('birthday')) has-error @endif">
                    <label for="birthday">{{ trans('messages.birthday') }}</label>
                    <div class="input-group date datepicker"  data-date-format="yyyy-mm-dd" data-provide="datepicker">
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
                        <input type="text" class="form-control p-street-address" name="address1" id="address1"
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
                        <input type="text" class="form-control p-extended-address" name="address2" id="address2"
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
                    <input type="text" class="form-control" name="memo" id="memo"
                           value="{{ old('memo', ( isset($customer_detail) ? $customer_detail->memo : '')) }}"/>
                    @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group py-sm-1 @if ($errors->has('claim_count')) has-error @endif">
                    <div class="form-group py-sm-1 @if ($errors->has('claim_count')) has-error @endif">
                        <label for="claim_count">{{ trans('messages.claim_count') }}
                        </label>
                        <input type="text" class="form-control" name="claim_count" id="claim_count"
                               value="{{ old('claim_count', ( isset($customer_detail) ? $customer_detail->claim_count : '')) }}"/>
                        @if ($errors->has('claim_count')) <p class="help-block">{{ $errors->first('claim_count') }}</p> @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group py-sm-1 @if ($errors->has('recall_count')) has-error @endif">
                    @if ($errors->has('recall_count')) <p class="help-block">{{ $errors->first('recall_count') }}</p> @endif
                    <label for="name">{{ trans('messages.recall_count') }}
                    </label>
                    <input type="text" class="form-control" name="recall_count" id="recall_count"
                           value="{{ old('recall_count', ( isset($customer_detail) ? $customer_detail->recall_count : '')) }}"/>
                    @if ($errors->has('recall_count')) <p class="help-block">{{ $errors->first('recall_count') }}</p> @endif
                </div>
            </div>
        </div>
    </div>
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
        })(jQuery);
    </script>
@endpush
@push('css')
    <style>
        p.help-block {
            text-align: left;
        }
        table.form-table td>div.row{
            margin-left: -5px;
            margin-right: -5px;
        }
        table.form-table>tbody>tr>td {
            vertical-align: middle;
            padding: 15px 8px;

        }
        td>.form-group {
            margin-bottom: 0px;
        }
    </style>
@endpush