<div class="body-footer-wrapper tab-pane" id="name-identification">

    <div class="modal-body">
        <div class="table-responsive">

            <h3>顧客の名寄せ</h3>
            @include('layouts.partials.pagination-label', ['paginator' => $name_identifications])
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-5 mt-3">
                    <tr>
                        <th>ID</th>
                        <th>{{ trans('messages.consultation_ticket_number') }}</th>
                        <th>{{ trans('messages.name') }}</th>
                        <th>{{ trans('messages.email') }}</th>
                        <th>{{ trans('messages.phone_number') }}</th>
                    </tr>
                    @if ( !empty($name_identifications) )
                        @foreach( $name_identifications as $name_identification )
                            <tr>
                                <td>{{ $name_identification->id }}</td>
                                <td>{{ $name_identification->registration_card_number }}</td>
                                <td>
                                    <a href="#" class="@if($customer_detail->name == $name_identification->name) text-red @endif">
                                        {{ $name_identification->name }}
                                    </a>
                                </td>
                                <td class="@if($customer_detail->email == $name_identification->email) text-red @endif">
                                    {{ $name_identification->email or '-' }}
                                </td>
                                <td class="@if($customer_detail->tel == $name_identification->tel) text-red @endif">
                                    {{ $name_identification->tel or '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4">{{ trans('messages.no_record') }}</td></tr>
                    @endif
                </table>
                <div class="right-paginate-bar name-identification ajax-paginator">
                    {{ $name_identifications->links() }}
                </div>
            </div>

        </div>
    </div>
</div>