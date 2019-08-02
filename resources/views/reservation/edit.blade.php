@extends('layouts.form')

@section('content_header')
  <h1>受信 &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
@stop

@section('form')

    <div class="temp-fixed-alert">
        @include('layouts.partials.message')
    </div>

  <form method="POST" action="{{ route('reservation.update', ['reservation' => $reservation->id]) }}">
  	{!! csrf_field() !!}
      {{ method_field('PATCH') }}
    @include('reservation.partials.edit-form')
  </form>
  @includeIf('reservation.partials.customer-script')
@stop


@push('css')
    <style>
        .temp-fixed-alert {
            margin-left: -30px;
            margin-top: -30px;
            margin-right: -30px;
        }
        .temp-fixed-alert .alert {
            position: relative;
            width: 100%;
            left: 0;
            top: 0;
        }
    </style>
@endpush
