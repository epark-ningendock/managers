@php
  use \App\Enums\Authority;
  use \App\Enums\Permission;
  use \App\Enums\ReservationStatus;
@endphp

@extends('layouts.list')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
  <h1>    
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <i class="fa fa-book"> 受付一覧</i>
  </h1>
@stop

<!-- search section -->
@section('search')
  <form role="form" id="search_form">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>予約日</label>
          <div class="form-inline">
            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd"
                 data-date-autoclose="true" data-date-language="ja">
              <input type="text" class="form-control"
                     id="reservation_created_start_date" name="reservation_created_start_date"
                     placeholder="yyyy/mm/dd" value="{{ $reservation_created_start_date or '' }}">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </div>
            </div>
            <span class="ml-2 mr-2">~</span>
            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd"
                 data-date-autoclose="true" data-date-language="ja">
              <input type="text" class="form-control"
                     id="reservation_created_end_date" name="reservation_created_end_date"
                     placeholder="yyyy/mm/dd" value="{{ $reservation_created_end_date or '' }}">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group">
          <label>受診日</label>
          <div class="form-inline">
            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd"
                 data-date-autoclose="true" data-date-language="ja">
              <input type="text" class="form-control"
                     id="reservation_start_date" name="reservation_start_date"
                     placeholder="yyyy/mm/dd" value="{{ $reservation_start_date or '' }}">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </div>
            </div>
            <span class="ml-2 mr-2">~</span>
            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd"
                 data-date-autoclose="true" data-date-language="ja">
              <input type="text" class="form-control"
                     id="reservation_end_date" name="reservation_end_date"
                     placeholder="yyyy/mm/dd" value="{{ $reservation_end_date or '' }}">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="customer_name">受診者名</label>
          <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="受診者名" max="64"
          value="{{ $customer_name or '' }}"/>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="course_id">検査コース名</label>
          <select class="form-control" name="course_id" id="course_id">
            <option></option>
            @foreach($courses as $course)
              <option value="{{ $course->id }}" @if(isset($course_id) && $course_id == $course->id) selected @endif>{{ $course->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group">
          <label>受付ステータス</label>
          <div class="form-inline">
            <div class="checkbox ml-2">
              <input type="checkbox" id="is_pending" name="is_pending" value="1" @if(isset($is_pending)) checked @endif/>
              <label for="is_pending">仮受付</label>
            </div>
            <div class="checkbox ml-2">
              <input type="checkbox" id="is_reception_completed" name="is_reception_completed" value="2" @if(isset($is_reception_completed)) checked @endif/>
              <label for="is_reception_completed">受付確定</label>
            </div>
            <div class="checkbox ml-2">
              <input type="checkbox" id="is_completed" name="is_completed" value="3" @if(isset($is_completed)) checked @endif />
              <label for="is_completed">完了</label>
            </div>
            <div class="checkbox ml-2">
              <input type="checkbox" id="is_cancelled" name="is_cancelled" value="4" @if(isset($is_cancelled)) checked @endif/>
              <label for="is_cancelled">キャンセル</label>
            </div>
          </div>

        </div>
      </div>

      <div class="col-md-12">
        <button type="button" id="clear" class="btn btn-default">検索条件のクリア</button>
        <button type="submit" class="btn btn-primary ml-4">
            <i class="glyphicon glyphicon-search"></i> 検索
        </button>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12">
        <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('reservation.create') }}">新規受付を登録する</a>
        <button class="btn btn-success ml-4 mr-4" id="csv_download">受付一覧をダウンロード</button>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12 mt-4">
        <div class="form-inline pull-right">
          <label for="record_per_page">表示件数</label>
          <select name="record_per_page" id="record_per_page" class="form-control mr-4 ml-4"
                  style="display: inline-block; width: auto;">
            @foreach([10, 20, 50, 100] as $num)
              <option @if(isset($record_per_page) && $record_per_page == $num) selected @endif>{{ $num }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-12 mt-4">
        <div class="pull-right">
          {{-- <button class="btn btn-primary" id="bulk-update">一括更新ボタン</button> --}}
          {{-- <select id="reservation_status_u" class="form-control mr-4 ml-4" style="display: inline-block; width: auto;">
            @foreach(ReservationStatus::toArray() as $key => $status_value)
              @if($key != 'Pending')
                <option value="{{ $status_value }}">{{ ReservationStatus::getInstance($status_value)->description }}</option>
              @endif
            @endforeach()
          </select> --}}
        </div>
      </div>

    </div>
  </form>
@stop

@section('table')
  <div class="table-responsive">
    <form id="bulk-status-form"  method="POST" action="{{ route('reservation.bulk_status') }}">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <input type="hidden" id="reservation_status" name="reservation_status" />
      @include('layouts.partials.pagination-label', ['paginator' => $reservations])
      <table id="example2" class="table table-bordered table-hover">
        <thead>
        <tr>
          {{-- <th>選択</th> --}}
          <th>予約ID</th>
          <th>受診日</th>
          <th>受診者名</th>
          <th>検査コース</th>
          <th>合計金額</th>
          <th>受付ステータス</th>
          <th>予約日</th>
          <th>編集</th>
          <th>ステータス変更</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reservations as $reservation)
          <tr>
            {{-- <td>
              <input type="checkbox" name="ids[]" id="ids_{{ $reservation->id }}" value="{{ $reservation->id }}" />
              <label for="ids_{{ $reservation->id }}"id="ids_{{ $reservation->id }}"></label>
            </td> --}}
            <td>{{ $reservation->id }}</td>
            <td>{{ $reservation->reservation_date->format('Y/m/d') }}</td>
            <td>{{ $reservation->customer->name}}</td>
            <td>{{ $reservation->course->name }}</td>
            <td>2000円</td>
            <td>{{ $reservation->reservation_status->description }}</td>
            <td>{{ $reservation->created_at->format('Y/m/d') }}</td>
            <td>
              <a class="btn btn-primary ml-3" href="{{ route('reservation.edit', ['reservation' => $reservation]) }}">
                変更
              </a>
            </td>
            <td>
              @if($reservation->reservation_status->is(ReservationStatus::Pending))
                <button class="btn btn-success ml-3 delete-popup-btn"
                        data-id="{{ $reservation->id }}" data-message="{{ trans('messages.reservation.accept_confirmation') }}"
                        data-target-form="#accept-form" data-button-text="確定する">
                  仮予約
                </button>
              @endif
              @if($reservation->reservation_status->is(ReservationStatus::ReceptionCompleted))
                <button class="btn btn-primary ml-3 delete-popup-btn"
                        data-id="{{ $reservation->id }}" data-message="{{ trans('messages.reservation.complete_confirmation') }}"
                        data-target-form="#complete-form" data-button-text="完了する">
                  受付確定
                </button>
              @endif
              @if($reservation->reservation_status->is(ReservationStatus::Completed))
                <button class="btn btn-primary ml-3 delete-popup-btn">
                  受診完了
                </button>
              @endif
              @if(!$reservation->reservation_status->is(ReservationStatus::Cancelled) && !$reservation->reservation_status->is(ReservationStatus::Completed))
                <button class="btn btn-danger ml-3 delete-popup-btn" data-id="{{ $reservation->id }}"
                        data-message="{{ trans('messages.reservation.cancel_confirmation') }}"
                        data-target-form="#cancel-form" data-button-text="キャンセルする">
                  キャンセル
                </button>
              @endif

            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </form>
    <form id="cancel-form" class="hide" method="POST"
          action="{{ route('reservation.cancel', ':id') }}">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
    </form>
    <form id="accept-form" class="hide" method="POST"
          action="{{ route('reservation.accept', ':id') }}">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
    </form>
    <form id="complete-form" class="hide" method="POST"
          action="{{ route('reservation.complete', ':id') }}">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
    </form>
  </div>
  {{ $reservations->links() }}
  <style>
    td, th {
      text-align: center;
    }
  </style>
@stop

@section('script')
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // record per page change
          -----------------------------------------------------*/
          (function(){
              $('#record_per_page').change(function(){
                  $('#search_form').submit();
              });
          })();

          /* ---------------------------------------------------
          // clear all input
          -----------------------------------------------------*/
          (function(){
              $('#clear').click(function(event){
                  event.preventDefault();
                  event.stopPropagation();
                  $('input, #course_id').val('');
                  $('input:checked').prop('checked', false);
              });
          })();

          /* ---------------------------------------------------
          // csv download
          -----------------------------------------------------*/
          (function(){
              $('#csv_download').click(function(){
                  event.preventDefault();
                  event.stopPropagation();
                  window.open('{{ route('reception.csv') }}' + '?' + $('#search_form').serialize(), '_black');
              });
          })();

          /* ---------------------------------------------------
          // bulk status update
          -----------------------------------------------------*/
          (function(){
              $('#bulk-update').click(function(){
                  event.preventDefault();
                  event.stopPropagation();
                  $('#reservation_status').val($('#reservation_status_u').val());
                  $('#bulk-status-form').submit();
              });
          })();

      })(jQuery);
  </script>
@stop

@include('commons.datepicker')
