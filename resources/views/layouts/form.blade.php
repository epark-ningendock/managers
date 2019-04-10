@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('content')
  <div class="box box-primary">
    <!-- Message -->
    @if ($errors->any())
      <div class="alert alert-danger">
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
