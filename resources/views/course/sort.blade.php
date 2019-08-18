@php
  use App\Enums\Status;
@endphp

@extends('layouts.list')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <span> 検査コース管理</span>
      -
      <span> 並び替え</span>
  </h1>
@stop
@section('table')
  <form method="post" action="{{ route('course.updateSort') }}">
    {!! csrf_field() !!}
    {!! method_field('PATCH') !!}
    <table class="table no-border table-hover table-striped">
      <thead>
      <tr>
        <th class="text-center">検査コース名 &gt; &GT;{{ request()->session()->get('hospital_name') }}</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        @foreach ($courses as $course)
          <tr>
            <td style="width: 80%">{{ $course->name }}</td>
            <td class="text-center" style="width: 20%">
              <input type="hidden" id="order" name="course_ids[]" value="{{ $course->id }}" />
              <button class="btn btn-default up"><span class="fa fa-fw fa-caret-up fa-lg"></span></button>
              <button class="btn btn-default ml-5 down"><span class="fa fa-fw fa-caret-down fa-lg"></span></button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="box-footer">
      <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">保存</button>
    </div>
  </form>
  <style>
    tr {
      cursor: move;
    }
    .ui-sortable-helper {
      display: table;
    }
  </style>
@stop
@section('js')
  @parent
  <script src="{{ asset('js/lib/jquery-ui.min.js') }}"></script>
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // move up/down and draggable
          -----------------------------------------------------*/
          (function () {
              const resetUpDown = function() {
                  $('.up, .down').removeAttr('disabled');
                  $('tr:first-child .up, tr:last-child .down').attr('disabled', 'disabled');
              }

              $(".up,.down").click(function(event){
                  event.preventDefault();
                  event.stopPropagation();

                  const row = $(this).parents("tr:first");
                  if ($(this).is(".up")) {
                      row.insertBefore(row.prev());
                  } else {
                      row.insertAfter(row.next());
                  }
                  resetUpDown();
              });

              $("tbody").sortable({
                  helper: 'clone',
                  distance: 5,
                  delay: 100,
                  cursor: 'move',
                  axis: "y",
                  containment: "parent",
                  items: "> tr",
                  update: resetUpDown
              }).disableSelection();

              resetUpDown();

          })();

      })(jQuery);
  </script>
@stop