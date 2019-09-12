<div class="body-footer-wrapper tab-pane" id="accepted-guidance-history">

    <div class="modal-body">
        <div class="table-responsive">

            <h5 class="std-title">受付案内履歴</h5>
            @include('layouts.partials.pagination-label', ['paginator' => $reservations])
            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-5 mt-3">
                    <tr>
                        <th>{{ trans('messages.reservation_date') }}</th>
                        <th>{{ trans('messages.name') }}</th>
                        <th>{{ trans('messages.course_name') }}</th>
                        <th>{{ trans('messages.reservation_status') }}</th>
                    </tr>
                    @if ( !empty($reservations) )

                        @foreach( $reservations as $reservation )
                            <tr>
                                <td>{{ $reservation->reservation_date }}</td>
                                <td>
                                    <a href="{{ route('reservation.edit', $reservation->id) }}">
                                        {{ $customer_detail->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ isset($reservation->course) ? $reservation->course->name : '-' }}
                                </td>
                                <td>{{ $reservation->reservation_status->description }}</td>
                            </tr>
                        @endforeach

                    @else

                        <tr><td colspan="4">{{ trans('messages.no_record') }}</td></tr>

                    @endif
                </table>
                <div class="right-paginate-bar reservation ajax-paginator">
                    {{ $reservations->links() }}
                </div>
            </div>

        </div>
    </div>

</div>