@extends('layouts.form')

@section('content_header')
  <h1>{{ trans('messages.update') }} &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
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
        {{-- Because there is "「削除」（Delete）" in the list, "「戻る」（Return）" is added here --}}
          <a href="{{ route('customer.index') }}" class="btn btn-default btn-lg">戻る</a>
      </div>

    </form>

@stop