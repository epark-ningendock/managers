@extends('layouts.master')

<!-- ページの内容を入力 -->
@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- Message -->
        @foreach (['error', 'success'] as $key)
          @if(Session::has($key))
            <div class="alert alert-{{ $key == 'error' ? 'danger' : $key }} alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
              {{ session($key) }}
            </div>
          @endif
        @endforeach
        <div class="box-header with-border">
          @yield('search')

          <a class="btn btn-success pull-right" href="{{ route($create_route) }}">新規作成</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          @yield('table')
        </div>
      </div>
    </div>
  </div>
  <form action="{{ route($delete_route, ':id') }}" method="post" id="delete-form">
    {!! csrf_field() !!}
    {!! method_field('DELETE') !!}
  </form>
@stop

<!-- 読み込ませるJSを入力 -->
@section('js')
  <script>
      $(document).ready(function() {
          $('.delete-btn').click(function(event) {
              event.preventDefault();
              event.stopPropagation();
              const id = $(this).data('id');
              Modal.showConfirm(function() {
                  submitDeleteForm(id);
              });
          });
      });

      function submitDeleteForm(id) {
          const action = $('#delete-form').attr('action').replace(':id', id);
          $('#delete-form').attr('action', action);
          $('#delete-form').submit();
      }
  </script>
@stop