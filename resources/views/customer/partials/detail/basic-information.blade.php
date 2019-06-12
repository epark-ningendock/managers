<div class="body-footer-wrapper tab-pane active" id="basic-information">

    <form method="post" action="{{ route('customer.update', ['id' => $customer_detail->id]) }}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

    <div class="modal-body">
        <div class="table-responsive">

            <table class="table table-bordered">
                <tr>
                    <td class="gray-cell-bg">{{ trans('messages.customer_id') }}</td>
                    <td>{{ $customer_detail->id }}</td>
                    <td class="gray-cell-bg">{{ trans('messages.registration_card_number') }}</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td  class="gray-cell-bg"><label for="name">{{ trans('messages.name') }}</label></td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" value="{{ ( !empty($customer_detail->name )) ? $customer_detail->name : old('name') }}" />
                        </div>
                    </td>
                    <td class="gray-cell-bg"><label for="name_kana">{{ trans('messages.name_kana') }}</label></td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name_kana" value="{{ ( !empty($customer_detail->name_kana )) ? $customer_detail->name_kana : old('name_kana') }}" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="gray-cell-bg"><label for="tel">{{ trans('messages.tel') }}</label></td>
                    <td colspan="3">
                        {{ $customer_detail->tel }}
                    </td>
                </tr>
                <tr>
                    <td class="gray-cell-bg"><label for="gender">{{ trans('messages.gender') }}</label></td>
                    <td>
                        {{ \App\Enums\Gender::getKey($customer_detail->gender) }}
                    </td>
                    <td class="gray-cell-bg">{{ trans('messages.birthday') }}</td>
                    <td>{{ $customer_detail->birthday }}</td>
                </tr>
                <tr>
                    <td class="gray-cell-bg">{{ trans('messages.postcode') }}</td>
                    <td colspan="3">{{ $customer_detail->postcode }}</td>
                </tr>
                <tr>
                    <td class="gray-cell-bg">{{ trans('messages.email') }}</td>
                    <td colspan="3"> {{ $customer_detail->email }}<br/> <button class="btn btn-primary">{{ trans('messages.send_mail') }}</button></td>
                </tr>
                <tr>
                    <td class="gray-cell-bg">{{ trans('messages.memo') }}</td>
                    <td>{{ $customer_detail->memo }}</td>
                    <td class="gray-cell-bg">{{ trans('messages.reservation_memo') }}</td>
                    <td>
                        @if ( !empty($customer_detail->reservations()->NearestDate()->first()->reservation_date) )
                            {{ $customer_detail->reservations()->NearestDate()->first()->reservation_date }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="gray-cell-bg">{{ trans('messages.claim_count') }}</td>
                    <td>{{ $customer_detail->claim_count }}</td>
                    <td class="gray-cell-bg">{{ trans('messages.recall_count') }}</td>
                    <td>{{ $customer_detail->recall_count }}</td>
                </tr>

            </table>

        </div>
    </div>
    <div class="modal-footer">
        <div class="ft-action-btn-wrapper">
            <button class="btn btn-primary" type="submit">Modify</button>
        </div>
    </div>

    </form>

</div>