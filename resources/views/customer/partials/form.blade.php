<div class="table-responsive">

    <table class="table table-bordered">
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.customer_id') }}</td>
            <td></td>
            <td class="gray-cell-bg">{{ trans('messages.consultation_ticket_number') }}</td>
            <td>
                <div class="form-group @if ($errors->has('registration_card_number')) has-error @endif">
                    <label for="registration_card_number">テキスト長</label>
                    <input type="text" class="form-control" name="registration_card_number" id="registration_card_number"
                           value="{{ old('registration_card_number', ( isset($customer_detail->registration_card_number) ? $customer_detail->registration_card_number : '')) }}"/>
                    @if ($errors->has('registration_card_number')) <p class="help-block">{{ $errors->first('registration_card_number') }}</p> @endif
                </div>
            </td>
        </tr>
        <tr>
            <td  class="gray-cell-bg"><label for="name">{{ trans('messages.name') }}<span class="text-danger">(*)</span></label></td>
            <td>

                <div class="form-group @if ($errors->has('name')) has-error @endif">
                    <label for="name">テキスト長</label>
                    <input type="text" class="form-control" name="name" id="name"
                           value="{{ old('name', ( isset($customer_detail->name) ? $customer_detail->name : '')) }}"/>
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                </div>

            </td>
            <td class="gray-cell-bg"><label for="name_kana">{{ trans('messages.name_kana') }}</label></td>
            <td>

                <div class="form-group @if ($errors->has('name_kana')) has-error @endif">
                    <label for="name_kana">テキスト長</label>
                    <input type="text" class="form-control" name="name_kana" id="name_kana"
                           value="{{ old('name_kana', ( isset($customer_detail->name_kana) ? $customer_detail->name_kana : '')) }}"/>
                    @if ($errors->has('name_kana')) <p class="help-block">{{ $errors->first('name_kana') }}</p> @endif
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
                    <label for="tel">テキスト長</label>
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
                    <label for="birthday">テキスト長</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="birthday" id="birthday"
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
                <div class="form-group @if ($errors->has('postcode')) has-error @endif">
                    <label for="postcode">テキスト長</label>
                    <input type="text" class="form-control" name="postcode" id="postcode"
                           value="{{ old('postcode', ( isset($customer_detail->postcode) ? $customer_detail->postcode : '')) }}"/>
                    @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
                </div>
            </td>
        </tr>

        <tr>
            <td class="gray-cell-bg">{{ trans('messages.prefectures') }}</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('prefecture_id')) has-error @endif">
                    <label for="prefecture_id">テキスト長</label>
                    <input type="text" class="form-control" name="prefecture_id" id="prefecture_id"
                           value="{{ old('prefecture_id', ( isset($customer_detail->prefecture_id) ? $customer_detail->prefecture_id : '')) }}"/>
                    @if ($errors->has('prefecture_id')) <p class="help-block">{{ $errors->first('prefecture_id') }}</p> @endif
                </div>
            </td>
        </tr>

        <tr>
            <td class="gray-cell-bg">（市区郡）</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('city_or_country')) has-error @endif">
                    <label for="city_or_country">テキスト長</label>
                    <input type="text" class="form-control" name="city_or_country" id="city_or_country"
                           value="{{ old('city_or_country', ( isset($customer_detail->city_or_country) ? $customer_detail->city_or_country : '')) }}"/>
                    @if ($errors->has('city_or_country')) <p class="help-block">{{ $errors->first('city_or_country') }}</p> @endif
                </div>
            </td>
        </tr>


        <tr>
            <td class="gray-cell-bg">（それ以降の住所）</td>
            <td colspan="3">
                <div class="form-group @if ($errors->has('address')) has-error @endif">
                    <label for="address">テキスト長</label>
                    <input type="text" class="form-control" name="address" id="address"
                           value="{{ old('address', ( isset($customer_detail->address) ? $customer_detail->address : '')) }}"/>
                    @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
                </div>
            </td>
        </tr>



        <tr>
            <td class="gray-cell-bg">{{ trans('messages.email') }}</td>
            <td colspan="3">

                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <label for="email">テキスト長</label>
                    <input type="text" class="form-control" name="email" id="email"
                           value="{{ old('email', ( isset($customer_detail->email) ? $customer_detail->email : '')) }}"/>
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>

            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.memo') }}</td>
            <td>
                <div class="form-group @if ($errors->has('memo')) has-error @endif">
                    <label for="memo">テキスト長</label>
                    <input type="text" class="form-control" name="memo" id="memo"
                           value="{{ old('memo', ( !empty($customer_detail->memo) ? $customer_detail->memo : '')) }}"/>
                    @if ($errors->has('memo')) <p class="help-block">{{ $errors->first('memo') }}</p> @endif
                </div>
            </td>
            <td class="gray-cell-bg">{{ trans('messages.reservation_memo') }}</td>
            <td>
                <div class="form-group @if ($errors->has('reservation_memo')) has-error @endif">
                    <label for="reservation_memo">テキスト長</label>
                    <input type="text" class="form-control" name="reservation_memo" id="reservation_memo"
                           value="{{ old('reservation_memo', ( isset($customer_detail->reservation_memo) ? $customer_detail->reservation_memo : '')) }}"/>
                    @if ($errors->has('reservation_memo')) <p class="help-block">{{ $errors->first('reservation_memo') }}</p> @endif
                </div>
            </td>
        </tr>
        <tr>
            <td class="gray-cell-bg">{{ trans('messages.claim_count') }}</td>
            <td>
                <div class="form-group @if ($errors->has('claim_count')) has-error @endif">
                    <label for="claim_count">テキスト長</label>
                    <input type="text" class="form-control" name="claim_count" id="claim_count"
                           value="{{ old('claim_count', ( isset($customer_detail->claim_count) ? $customer_detail->claim_count : '')) }}"/>
                    @if ($errors->has('claim_count')) <p class="help-block">{{ $errors->first('claim_count') }}</p> @endif
                </div>
            </td>
            <td class="gray-cell-bg">{{ trans('messages.recall_count') }}</td>
            <td>
                <div class="form-group @if ($errors->has('recall_count')) has-error @endif">
                    <label for="recall_count">テキスト長</label>
                    <input type="text" class="form-control" name="recall_count" id="recall_count"
                           value="{{ old('recall_count', ( isset($customer_detail->recall_count) ? $customer_detail->recall_count : '')) }}"/>
                    @if ($errors->has('recall_count')) <p class="help-block">{{ $errors->first('recall_count') }}</p> @endif
                </div>
            </td>
        </tr>

    </table>

</div>