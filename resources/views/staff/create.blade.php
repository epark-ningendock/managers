@extends('layouts.master')

@section('content_header')
  <h1>スタッフ情報</h1>
@stop

<!-- ページの内容を入力 -->
@section('content')

  <div class="box box-primary">
    @if ($errors->any())
      <br/>
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="box-header with-border">
      <h3 class="box-title">スタッフ管理</h3>
    </div>
    <!-- /.box-header -->
    <form method="POST" action="{{ route('staff.store') }}">
      @include('staff.partial.form')
    </form>

  </div>

@section('js')
  <script>
      $(document).ready(() => {
          (() => {
              const hasPermission = (permission, functionBit) => {
                  return (permission & functionBit) == functionBit;
              };

              const functionBits = {
                  view: 1,
                  edit: 2,
                  upload: 4
              };

              $('.permission').each(function(){
                  const permission = parseInt($(this).val());
                  const name = $(this).prop('name');
                  Object.keys(functionBits).forEach((key) => {
                      if (hasPermission(permission, functionBits[key])) {
                          $('#' + name + key).setAttribute('checked', 'checked');
                      }
                  });
              });
              $('.permission-check').change(function(){
                  var temp = this.prop('id');
                  const id = temp.substr(0, temp.lastIndexOf('_'));
                  var permission = parseInt($('#' + id).value());
                  const bit = this.checked ? this.val() : 0;
                  // permission = permission |
              });
          })();
      });
  </script>
@stop

@stop
