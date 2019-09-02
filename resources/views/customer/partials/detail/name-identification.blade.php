<div class="body-footer-wrapper tab-pane" id="name-identification">

    <div class="modal-body">
        <div class="table-responsive">

            <h3>顧客の名寄せ</h3>
            @include('layouts.partials.pagination-label', ['paginator' => $name_identifications])
            <div class="table-responsive">
                <form method="post">
                    {!! csrf_field() !!}
                    <table class="table table-bordered table-hover mb-5 mt-3">
                        <tr>
                            <th>{{ trans('messages.integrate') }}</th>
                            <th>ID</th>
                            <th>{{ trans('messages.consultation_ticket_number') }}</th>
                            <th>{{ trans('messages.name') }}</th>
                            <th>{{ trans('messages.email') }}</th>
                            <th>{{ trans('messages.phone_number') }}</th>
                            <th>{{ trans('messages.operation') }}</th>
                        </tr>
                        @if ( !empty($name_identifications) )
                            @foreach( $name_identifications as $name_identification )
                                <tr>
                                    <td>
                                        <input type="checkbox" class="identical_ids" value="{{ $name_identification->id }}"
                                               id="identical_ids_{{ $name_identification->id }}" name="identical_ids[]" />
                                        <label for="identical_ids_{{ $name_identification->id }}"></label>
                                    </td>
                                    <td>{{ $name_identification->id }}</td>
                                    <td>{{ $name_identification->registration_card_number }}</td>
                                    <td class="@if($customer_detail->name == $name_identification->name) text-red @endif">
                                        {{ $name_identification->name }}
                                    </td>
                                    <td class="@if($customer_detail->email == $name_identification->email) text-red @endif">
                                        {{ $name_identification->email or '-' }}
                                    </td>
                                    <td class="@if($customer_detail->tel == $name_identification->tel) text-red @endif">
                                        {{ $name_identification->tel or '-' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-primary">
                                            {{ trans('messages.display_switching') }}
                                        </button>
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
                    <div class="text-center">
                        <button class="btn btn-primary" id="perform-integration">
                            {{ trans('messages.perform_identification') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>