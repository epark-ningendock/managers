@extends('layouts.form')

@section('content_header')
  <h1>{{ trans('messages.create_new') }} &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
@stop

@section('form')

    <div class="note-msg m-4">
      <span class="text-danger">必須</span>の欄は必ず入力してください
    </div>
    <form method="POST" action="{{ route('customer.store') }}" class="h-adr">
      {{ csrf_field() }}

      @includeIf('customer.partials.form')

      <div class="text-center mb-5 pb-5">
        <a href="{{ route('customer.index') }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">{{trans('messages.registration') }}</button>
      </div>

    </form>

@stop