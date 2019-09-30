@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('main-content')

  <div class="box box-primary form-box">

    @include('layouts.partials.message')
    <div class="inner-box">

      <!-- Error -->
      {{-- @include('layouts.partials.errorbag') --}}

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
              <li>{{ trans('messages.input-required') }}</li>
          </ul>
        </div>
      @endif


      @yield('form')

    </div>

  </div>
@stop
