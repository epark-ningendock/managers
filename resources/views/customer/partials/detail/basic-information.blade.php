<div class="body-footer-wrapper tab-pane active" id="basic-information">

    <form method="post" action="{{ route('customer.update', ['id' => $customer_detail->id]) }}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

    <div class="modal-body">
        <div class="table-responsive">

            <table class="table table-bordered basic-info">
                <tr>
                    <td class="gray-cell-bg">{{ trans('messages.customer_id') }}</td>
                    <td>{{ $customer_detail->id }}</td>
                    <td class="gray-cell-bg">{{ trans('messages.registration_card_number') }}</td>
                    <td>{{ $customer_detail->registration_card_number }}</td>
                </tr>
                <tr>
                    <td  class="gray-cell-bg"><label for="name">{{ trans('messages.name') }}</label></td>
                    <td>
                        {{ $customer_detail->name }}
                    </td>
                    <td  class="gray-cell-bg"><label for="name">{{ trans('messages.name_kana') }}</label></td>
                    <td>
                        {{ $customer_detail->name_kana }}
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
                        {{ $customer_detail->sex->description }}
                    </td>
                    <td class="gray-cell-bg">{{ trans('messages.birthday') }}</td>
                    <td>{{ $customer_detail->birthday }}</td>
                </tr>
                <tr>
                    <td class="gray-cell-bg">{{ trans('messages.address') }}</td>
                    <td colspan="3">
                        {{ trans('messages.postcode') }} - {{ $customer_detail->postcode }}<br/>
                        {{ trans('messages.prefectures') }} - {{ $customer_detail->prefecture_id }}<br/>
                        {{ $customer_detail->address1 }}<br/>
                        {{ $customer_detail->address2 }}<br/>
                    </td>
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
<style>
    table.basic-info>tbody>tr>td {
        text-align: left;
        vertical-align: middle;
    }
    table.basic-info td.gray-cell-bg {
        background-color:  #e5e2e2;
        text-align: right;
    }
</style>