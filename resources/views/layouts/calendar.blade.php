@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('main-content')
        <!-- Message -->
        @include('layouts.partials.message')
        @include('layouts.partials.errorbag')
        <div class="box-header with-border">
          @yield('search')
          @section('button')
            @if(isset($create_route))
              @if(isset($route) && $route === "staff")
                @if (Auth::user()->staff_auth->is_staff === 3)
                  <a class="btn btn-primary pull-right" href="{{ route($create_route) }}">新規作成</a>
                @endif
              @else
                <a class="btn btn-primary pull-right" href="{{ route($create_route) }}">新規作成</a>
              @endif
            @endif
          @show
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          @yield('table')
        </div>
  @if(isset($delete_route))
    <form id="delete-record-form" class="hide" method="POST"
          {{--action="{{ route($delete_route, ':id').'?'.(isset($delete_params) ? $delete_params : '') }}">--}}
          action="{{ route($delete_route, ':id') }}">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
    </form>
  @endif
@stop