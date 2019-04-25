@php
  use App\Enums\Status;
  $classification = !isset($classification) ? 'minor' : $classification;
@endphp

@extends('layouts.list')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>検査コース分類管理</h1>
@stop

<!-- search section -->
@section('search')
  <form role="form">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="type">分類種別</label>
          <select class="form-control" id="type" name="type">
            <option value="">なし</option>
            @foreach($c_types as $c_type)
              <option
                  value="{{ $c_type->id }}" {{ (isset($type) && $type == $c_type->id) ? "selected" : "" }}>{{ $c_type->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="classification">分類</label>
          <select class="form-control" id="classification" name="classification">
            <option value="major" {{ $classification == 'major' ? "selected" : "" }}>大分類
            </option>
            <option value="middle" {{ $classification == 'middle' ? "selected" : "" }}>中分類
            </option>
            <option value="minor" {{ $classification == 'minor' ? "selected" : "" }}>小分類
            </option>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="major">大分類</label>
          <select class="form-control" id="major" name="major">
            <option value="">なし</option>
            @foreach($c_majors as $c_major)
              <option data-type-id="{{ $c_major->classification_type_id }}"
                  value="{{ $c_major->id }}" {{ (isset($major) && $major == $c_major->id) ? "selected" : "" }}>{{ $c_major->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="middle">中分類</label>
          <select class="form-control" id="middle" name="middle">
            <option value="">なし</option>
            @foreach($c_middles as $c_middle)
              <option data-major-id="{{$c_middle->major_classification_id}}"
                  value="{{ $c_middle->id }}" {{ (isset($middle) && $middle == $c_middle->id) ? "selected" : "" }}>{{ $c_middle->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-md-1">
        <button type="submit" class="btn btn-primary btn-search">検索</button>
      </div>
    </div>
  </form>
@stop

@section('table')
  <form method="post" action="{{ route('classification.updateSort') }}">
    {!! csrf_field() !!}
    {!! method_field('PATCH') !!}
    <input type="hidden" name="classification" value="{{ $classification }}" />
    <table class="table table-bordered table-hover">
      <thead>
      <tr>
        <th class="text-center">分類分</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        @foreach ($classifications as $i=>$item)
          <tr class="{{ $item['status']->is(Status::Deleted) ? 'dark-gray' : '' }}">
            <td style="width: 80%">{{ $item[$classification.'_name'] }}</td>
            <td class="text-center" style="width: 20%">
              <input type="hidden" id="order" name="classification_ids[]" value="{{ $item['id'] }}" />
              <button class="btn btn-default up"><span class="fa fa-fw fa-caret-up fa-lg"></span></button>
              <button class="btn btn-default ml-5 down"><span class="fa fa-fw fa-caret-down fa-lg"></span></button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="box-footer pull-right">
      <a href="{{ url()->previous() }}" class="btn btn-default">バック</a>
      <button type="submit" class="btn btn-primary">つくる</button>
    </div>
  </form>
  <style>
    tr.dark-gray td {
      background-color: darkgray;
    }
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
          // classification change
          -----------------------------------------------------*/
          (function () {
              const cEle = $('#classification');
              const onChange = function () {
                  if (cEle.val() == 'major') {
                      $('#major, #middle').attr('disabled', 'disabled');
                      $('#major, #middle').val('');
                  } else if (cEle.val() == 'middle') {
                      $('#middle').attr('disabled', 'disabled');
                      $('#middle').val('');
                      $('#major').removeAttr('disabled');
                  } else {
                      $('#major, #middle').removeAttr('disabled');
                  }
              };
              cEle.change(onChange);

              const typeEle = $('#type');
              const onTypeChange = function() {
                  const selected = typeEle.val();
                  if (selected == ''){
                      $('#major option').show();
                  } else {
                      $('#major option').each(function(i, option) {
                          option = $(option)
                          if (option.val() == '' || option.data('type-id') == selected) {
                              option.show();
                          } else {
                              option.hide();
                              if (option.is(':selected')){
                                  option.removeAttr('selected');
                                  $('#major').val('');
                              }
                          }
                      });
                  }
                  onMajorChange();
              };
              typeEle.change(onTypeChange);

              const majorEle = $('#major');
              const onMajorChange = function() {
                  const selected = majorEle.val();
                  if (selected == '' && typeEle.val() == ''){
                      $('#middle option').show();
                  } else {
                      let ids = []
                      if (selected == '') {
                          //:visible selector doesn't work
                          const options = majorEle.find('option:not([style*="display: none"])');
                          options.each(function(i, option) {
                              option = $(option);
                             if (option.val() != '') {
                                 ids.push(option.val());
                             }
                          });
                      } else {
                          ids = [ selected ];
                      }
                      $('#middle option').each(function(i, option) {
                          option = $(option);
                          if (option.val() == '' || ids.includes(option.data('major-id').toString())) {
                              option.show();
                          } else {
                              option.hide();
                              if (option.is(':selected')){
                                  option.removeAttr('selected');
                                  $('#middle').val('');
                              }
                          }
                      });
                  }
              };
              majorEle.change(onMajorChange);

              onChange();
              onTypeChange();
              onMajorChange();
          })();

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