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
    <h1>{{ trans('messages.names.customers') }} &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
@stop

@section('search')


    @includeIf('customer.partials.action-bar')
    @includeIf('customer.partials.listing-search')


@stop


@section('table')

    @includeIf('customer.partials.count-pagination-bar')

    <div class="table-responsive">
        <table id="example2" class="table table-bordered table-hover table-striped mb-5 mt-5">
            <thead>
            <tr>
                <th>{{ trans('messages.customer_id') }}</th>
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
                <th>
                    <a href="{{ route('customer.index', ['birthday_sorting' => columnSorting('birthday_sorting')]) }}">
                        {{ trans('messages.birthday') }}
                    </a>
                </th>
                <th>
                    <a href="{{ route('customer.index', ['email_sorting' => columnSorting('email_sorting')]) }}">
                        {{ trans('messages.email') }}
                    </a>
                </th>
                <th>{{ trans('messages.gender') }}</th>
                <th>
                    <a href="{{ route('customer.index', ['updated_at_sorting' => columnSorting('updated_at_sorting')]) }}">
                        {{ trans('messages.updated_at') }}
                    </a>
                </th>
                <th>{{ trans('messages.registration_form') }}</th>
                <th>{{ trans('messages.edit') }}</th>
                <th>{{ trans('messages.delete') }}</th>
            </tr>
            </thead>
            <tbody>

            @if ( isset($customers) && count($customers) > 0 )
                @foreach ($customers as $customer)

                    <tr class="customer-{{ $customer->id }}">
                        <td>{{ $customer->id }}</td>
                        <td>
                            <a class="detail-link" href="#" data-id="{{ $customer->id }}" data-route="{{ route('customer.detail') }}">
                                {{ $customer->name }}
                            </a>
                        </td>
                        <td>{{ $customer->registration_card_number }}</td>
                        <td>{{ $customer->tel }}</td>
                        <td>{{ $customer->birthday }}</td>
                        <td>
                            <a href="#" class="send-email"  data-id="{{ $customer->id }}" data-route="{{ route('customer.show.email.form', ['customer_id' => $customer->id]) }}">
                                {{ $customer->email }}
                            </a>
                        </td>
                        <td>{{ \App\Enums\Gender::getKey($customer->sex) }}</td>
                        <td>{{ date('Y/m/d', strtotime($customer->updated_at)) }}</td>
                        <td>{{ $customer->parent_customer_id }}</td>
                        <td>
                            <a class="btn btn-primary"
                               href="{{ route('customer.edit', $customer->id) }}">
                                編集
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $customer->id }}">
                                削除
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
    </div>

    @includeIf('customer.partials.detail.detail-popup')
    @includeIf('customer.partials.detail.detail-popup-script')


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