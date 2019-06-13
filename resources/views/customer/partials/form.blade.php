<div class="table-responsive">

    <table class="table table-bordered">
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.customer_id') }}</td>
            <td></td>
            <td class="gray-cell-bg">{{ trans('messages.consultation_ticket_number') }}</td>
            <td>
                <div class="form-group @if ($errors->has('registration_card_number')) has-error @endif">
                    <input type="text" class="form-control" name="registration_card_number" id="registration_card_number"
                           value="{{ old('registration_card_number', ( isset($customer_detail->registration_card_number) ? $customer_detail->registration_card_number : '')) }}"/>
                    @if ($errors->has('registration_card_number')) <p class="help-block">{{ $errors->first('registration_card_number') }}</p> @endif
                </div>
            </td>
        </tr>
        <tr>
            <td  class="gray-cell-bg"><label for="name">{{ trans('messages.name') }}<span class="text-danger">(*)</span></label></td>
            <td>

                <div class="form-group @if ($errors->has('name_seri')) has-error @endif">
                    <input type="text" class="form-control" name="name_seri" id="name_seri"
                           value="{{ old('name_seri', ( isset($customer_detail->name_seri) ? $customer_detail->name_seri : '')) }}"/>
                    @if ($errors->has('name_seri')) <p class="help-block">{{ $errors->first('name_seri') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('name_mei')) has-error @endif">
                    <input type="text" class="form-control" name="name_mei" id="name_mei"
                           value="{{ old('name_mei', ( isset($customer_detail->name_mei) ? $customer_detail->name_mei : '')) }}"/>
                    @if ($errors->has('name_mei')) <p class="help-block">{{ $errors->first('name_mei') }}</p> @endif
                </div>

            </td>
            <td class="gray-cell-bg"><label for="name_kana">{{ trans('messages.name_kana') }}</label></td>
            <td>

                <div class="form-group @if ($errors->has('name_seri')) has-error @endif">
                    <input type="text" class="form-control" name="name_kana_seri" id="name_kana_seri"
                           value="{{ old('name_kana_seri', ( isset($customer_detail->name_kana_seri) ? $customer_detail->name_kana_seri : '')) }}"/>
                    @if ($errors->has('name_kana_seri')) <p class="help-block">{{ $errors->first('name_kana_seri') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('name_kana_mei')) has-error @endif">
                    <input type="text" class="form-control" name="name_kana_mei" id="name_kana_mei"
                           value="{{ old('name_kana_mei', ( isset($customer_detail->name_kana_mei) ? $customer_detail->name_kana_mei : '')) }}"/>
                    @if ($errors->has('name_kana_mei')) <p class="help-block">{{ $errors->first('name_kana_mei') }}</p> @endif
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
                           value="{{ old('tel', ( isset($customer_detail->tel) ? $customer_detail->tel : '')) }}"/>
                    @if ($errors->has('tel')) <p class="help-block">{{ $errors->first('tel') }}</p> @endif
                </div>

            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg"><label for="gender">{{ trans('messages.gender') }}</label></td>
            <td>
                <div class="form-group @if ($errors->has('sex')) has-error @endif">
                    <label for="sex">状態</label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="sex"
                                   value="M"
                            @if ( isset($customer_detail->sex) && ( $customer_detail->sex == 'M' ) )
                                checked="checked"
                            @endif
                            />
                            {{ trans('messages.male') }}
                        </label>
                        <label class="ml-3">
                            <input type="radio" name="sex"
                                   value="F"
                                   @if ( isset($customer_detail->sex) && ( $customer_detail->sex == 'F' ) )
                                   checked="checked"
                                    @endif
                            />
                            {{ trans('messages.female') }}
                        </label>
                    </div>
                    @if ($errors->has('sex')) <p class="help-block">{{ $errors->first('sex') }}</p> @endif
                </div>
            </td>
            <td class="gray-cell-bg">{{ trans('messages.birthday') }}</td>
            <td>

                <div class="form-group @if ($errors->has('birthday')) has-error @endif">
                    <div class="input-group date datepicker"  data-date-format="yyyy-mm-dd" data-provide="datepicker">
                        <input type="text" class="form-control date-picker" name="birthday" id="birthday"
                               value="{{ old('birthday', ( isset($customer_detail->birthday) ? $customer_detail->birthday : '')) }}"/>
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
                <div class="form-group">
                    <input type="text" class="form-control" name="postcode1" id="postcode1"
                           value="{{ old('postcode1', ( isset($customer_detail->postcode) ? substr($customer_detail->postcode, 0, 3) : '')) }}" style="display: inline-block; width: 80px;"/> -
                    <input type="text" class="form-control" name="postcode2" id="postcode2"
                           value="{{ old('postcode2', ( isset($customer_detail->postcode) ? substr($customer_detail->postcode, 3) : '')) }}" style="display: inline-block; width: 80px;"/>
                </div>
            </td>
        </tr>

        <tr>
            <td class="gray-cell-bg">{{ trans('messages.prefectures') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('prefecture_id')) has-error @endif">
                    <select name="prefecture_id" id="prefecture_id" class="form-control">
                        <option value=""></option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->prefecture_id }}"
                                @if( $customer->prefecture_id === $customer_detail->prefecture_id )
                                    selected="selected"    
                                    @endif
                            >{{ $customer->prefecture_id }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('prefecture_id')) <p class="help-block">{{ $errors->first('prefecture_id') }}</p> @endif
                </div>
            </td>
        </tr>


        <tr>
            <td class="gray-cell-bg">{{ trans('messages.address') }} 1</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('address1')) has-error @endif">
                    <input type="text" class="form-control" name="address1" id="address1"
                           value="{{ old('address1', ( isset($customer_detail->address1) ? $customer_detail->address1 : '')) }}"/>
                    @if ($errors->has('address1')) <p class="help-block">{{ $errors->first('address1') }}</p> @endif
                </div>
            </td>
        </tr>

        <tr>
            <td class="gray-cell-bg">{{ trans('messages.address') }} 2</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('address2')) has-error @endif">
                    <input type="text" class="form-control" name="address2" id="address2"
                           value="{{ old('address2', ( isset($customer_detail->address2) ? $customer_detail->address2 : '')) }}"/>
                    @if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
                </div>
            </td>
        </tr>



        <tr>
            <td class="gray-cell-bg">{{ trans('messages.email') }}</td>
            <td colspan="3">

                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <input type="text" class="form-control" name="email" id="email"
                           value="{{ old('email', ( isset($customer_detail->email) ? $customer_detail->email : '')) }}"/>
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>

            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.memo') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('memo')) has-error @endif">
                    <input type="text" class="form-control" name="memo" id="memo"
                           value="{{ old('memo', ( !empty($customer_detail->memo) ? $customer_detail->memo : '')) }}"/>
                    @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
                </div>
            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.claim_count') }}</td>
            <td>
                <div class="form-group @if ($errors->has('claim_count')) has-error @endif">
                    <input type="text" class="form-control" name="claim_count" id="claim_count"
                           value="{{ old('claim_count', ( isset($customer_detail->claim_count) ? $customer_detail->claim_count : '')) }}"/>
                    @if ($errors->has('claim_count')) <p class="help-block">{{ $errors->first('claim_count') }}</p> @endif
                </div>
            </td>
            <td class="gray-cell-bg">{{ trans('messages.recall_count') }}</td>
            <td>
                <div class="form-group @if ($errors->has('recall_count')) has-error @endif">
                    <input type="text" class="form-control" name="recall_count" id="recall_count"
                           value="{{ old('recall_count', ( isset($customer_detail->recall_count) ? $customer_detail->recall_count : '')) }}"/>
                    @if ($errors->has('recall_count')) <p class="help-block">{{ $errors->first('recall_count') }}</p> @endif
                </div>
            </td>
        </tr>

    </table>

</div>

@includeIf('commons.datepicker')