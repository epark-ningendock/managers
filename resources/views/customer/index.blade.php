@php
    use \App\Enums\HospitalEnums;
    $params = [
        'delete_route' => 'customer.destroy'
    ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', trans('messages.names.customers'))

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        -
        <i class="fa fa-users"> 顧客管理</i>
    </h1>
@stop

@section('search')
    @includeIf('customer.partials.listing-search')
@stop


@section('table')
    <div class="table-responsive">
        @include('layouts.partials.pagination-label', ['paginator' => $customers])
        <table id="example2" class="table no-border table-hover table-striped mb-5">
            <thead>
            <tr>
                <th>顧客ID</th>
                <th>
                    <a href="{{ route('customer.index', ['name_sorting' => columnSorting('name_sorting')]) }}">
                        {{ trans('messages.name') }}
                    </a>
                </th>
                <th>
                    <a href="{{ route('customer.index', ['registration_card_number_sorting' => columnSorting('registration_card_number_sorting')]) }}">
                        {{ trans('messages.registration_card_number') }}
                    </a>
                </th>
                <th>{{ trans('messages.phone_number') }}</th>
                <th>{{ trans('messages.birthday') }}</th>
                {{--<th>--}}
                    {{--<a href="{{ route('customer.index', ['email_sorting' => columnSorting('email_sorting')]) }}">--}}
                        {{--{{ trans('messages.email') }}--}}
                    {{--</a>--}}
                {{--</th>--}}
                <th>{{ trans('messages.gender') }}</th>
                <th>生年月日</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @includeIf('customer.partials.action-bar')
            @if ( isset($customers) && count($customers) > 0 )
                @foreach ($customers as $customer)

                    <tr class="customer-{{ $customer->id }}">
                        <td>{{ $customer->id }}</td>
                        <td>
                            {{ $customer->id }}
                        </td>
                        <td>
                            <a class="detail-link" href="#" data-id="{{ $customer->id }}" data-route="{{ route('customer.detail') }}">
                                {{ $customer->name }}
                            </a>
                        </td>
                        <td>{{ $customer->registration_card_number }}</td>
                        <td>{{ $customer->tel }}</td>
                        <td>{{ $customer->birthday }}</td>
                        <td>{{ $customer->sex->description or '-' }}</td>
                        <td>{{ $customer->birthday }}</td>
                        <td>
                            <a class="btn btn-primary"
                               href="{{ route('customer.edit', $customer->id) }}">
                                <i class="fa fa-edit"> 編集</i>
                            </a>
                            <button class="btn btn-primary delete-btn delete-popup-btn" data-id="{{ $customer->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11" class="text-center">{{ trans('messages.no_record') }}</td>
                </tr>
            @endif

            </tbody>
        </table>
        {{ $customers->appends(request()->input())->links() }}
    </div>

    @includeIf('customer.partials.detail.detail-popup')
    @includeIf('customer.partials.detail.detail-popup-script')

    @include('customer.partials.email-history-detail')

    @includeIf('commons.std-modal-box')
    @includeIf('customer.partials.email-popup-script')

@stop

@includeIf('commons.datepicker')

@push('js')
    <script type="text/javascript">

        (function ($) {

            /* ---------------------------------------------------
             Pagination Selected number change url
            -----------------------------------------------------*/
            $('#paginate-selection').change(function () {
                location.href = $(this).val();
            });

        })(jQuery);


    </script>

@endpush