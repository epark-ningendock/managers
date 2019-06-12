@extends('layouts.form')

@section('content_header')
  <h1>{{ trans('messages.update') }}</h1>
@stop

@section('form')

    <div class="note-msg m-4">
      <span class="text-danger">(*)</span>必ず列を入力してください
    </div>
    <form method="post" action="{{ route('customer.update', ['id' => $customer_detail->id]) }}">
      {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @includeIf('customer.partials.form')

      <div class="text-center mb-5 pb-5">
        <button type="submit" class="btn btn-primary btn-lg">{{trans('messages.update') }}</button>
          <a href="{{ route('customer.index') }}" class="btn btn-default btn-lg">{{ trans('messages.remove') }}</a>
      </div>

    </form>

@stop