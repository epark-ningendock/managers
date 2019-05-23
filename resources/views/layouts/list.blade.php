@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('main-content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- Message -->
        @include('layouts.partials.message')
        @include('layouts.partials.errorbag')
        <div class="box-header with-border">
          @yield('search')
          @section('button')
            @if(isset($create_route))
              <a class="btn btn-success pull-right" href="{{ route($create_route) }}">新規作成</a>
            @endif
          @show
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          @yield('table')
        </div>
      </div>
    </div>
  </div>
  @if(isset($delete_route))
    <form id="delete-record-form" class="hide" method="POST"
          action="{{ route($delete_route, ':id').'?'.(isset($delete_params) ? $delete_params : '') }}">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
    </form>
  @endif
@stop