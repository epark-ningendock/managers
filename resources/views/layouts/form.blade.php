@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('content')
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">{{ $box_title }}</h3>
    </div>
    <!-- Message -->
    @if ($errors->any())
      <div class="alert alert-danger mt-5">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif



    @yield('form')

  </div>
@stop
