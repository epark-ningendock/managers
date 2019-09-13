@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <span> 顧客管理</span>
  </h1>
@stop

@section('form')
    <form method="post" action="{{ route('customer.update', ['id' => $customer_detail->id]) }}" class="h-adr">
      {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @includeIf('customer.partials.form')

      <div class="text-center mb-5 pb-5">
        {{-- Because there is "「削除」（Delete）" in the list, "「戻る」（Return）" is added here --}}
        <a href="{{ route('customer.index') }}" class="btn btn-default">戻る</a>
        <button type="submit" class="btn btn-primary">保存</button>
      </div>

    </form>

@stop