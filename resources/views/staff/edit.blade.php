@extends('layouts.form', [ 'box_title' => 'スタッフ管理' ])

@section('content_header')
  <h1>スタッフ情報</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('staff.update', $staff->id) }}">
    {!! method_field('PATCH') !!}
    @include('staff.partial.form')
  </form>
@stop

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
