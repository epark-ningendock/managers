@php
  use App\Enums\StaffStatus;
  use App\Enums\Authority;
  use \App\Enums\Permission;
  use Carbon\Carbon;
@endphp

@extends('layouts.form')

@section('content_header')
  <h1>    
    <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
    -
    <i class="fa fa-calendar"> カレンダー管理</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('calendar.setting', $calendar->id) }}">
    {!! csrf_field() !!}
    {!! method_field('PATCH') !!}
    <input type="hidden" name="lock_version" value="{{ $calendar->lock_version or '' }}" />
    <div class="box-body">
      <div class="form-group">
        <h4>カレンダー名 <span class="ml-2 mr-2"> : </span> {{ $calendar->name }}</h4>
      </div>
      <h4>期間 <span class="ml-2 mr-2"> : </span> {{ $start->format('Y/m/d').' ~ '.$end->format('Y/m/d') }}</h4>
      {!! csrf_field() !!}
      <hr>
      <h4>一括反映</h4>
      <div class="row">
        <div class="col-md-6">
          <table class="table table-bordered top-table">
            <thead>
            <tr>
              <th class="text-red">日</th>
              <th>月</th>
              <th>火</th>
              <th>水</th>
              <th>木</th>
              <th>金</th>
              <th class="text-blue">土</th>
              <th class="text-red">祝</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>
                <select id="sunday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select id="monday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select id="tuesday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select id="wednesday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select id="thursday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select id="friday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select id="saturday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select id="holiday-frame">
                  <option></option>
                  @foreach(range(0, 99) as $i)
                    <option>{{ $i }}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-3">
          <table class="table table-borderless">
            <tr class="month-week">
              <td>
                <h4>対象月</h4>
                <label><input type="checkbox" id="all-month"/> すべて選択</label>
                @foreach($months->keys() as $index => $month)
                  <label><input type="checkbox" id="month-{{ $index }}" class="month" data-index="{{ $index }}"/> {{ $month }}</label>
                @endforeach
              </td>
              <td>
                <h4>対象週</h4>
                <label><input type="checkbox" class="week" id="all-week" /> すべて選択</label>
                <label><input type="checkbox" class="week" id="week-1" /> 第1 (1日~7日)</label>
                <label><input type="checkbox" class="week" id="week-2" /> 第2 (8日~14日)</label>
                <label><input type="checkbox" class="week" id="week-3" /> 第3 (15日~21日)</label>
                <label><input type="checkbox" class="week" id="week-4" /> 第4 (22日~28日)</label>
                <label><input type="checkbox" class="week" id="week-5" /> 第5 (29日~)</label>
              </td>
            </tr>
          </table>
          <button class="btn btn-primary pull-right" id="bulk-update">一括反映</button>
        </div>
      </div>
      <hr />

      <div class="row">
        @php
          $index = 1
        @endphp
        @foreach($months as $key => $month)
          <div class="col-md-6">
              <h4 class="text-center">{{ $key }}</h4>
              <table class="table table-bordered calendar-table">
                <thead>
                  <tr>
                    <th class="text-red">日</th>
                    <th>月</th>
                    <th>火</th>
                    <th>水</th>
                    <th>木</th>
                    <th>金</th>
                    <th class="text-blue">土</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($month->chunk(7) as $week)
                  <tr>
                    @foreach($week as $day)
                      @if($day != null)
                        <td class="@if($day['date']->isSunday()) holiday @elseif($day['date']->isSaturday()) saturday @endif">
                          <!-- date -->
                          <input type="hidden" name="days[]" value="{{ $day['date']->format('Ymd') }}" />
                          <span class="day-label @if($day['date']->isSunday()) text-red @elseif($day['date']->isSaturday()) text-blue @endif">
                            {{ $day['date']->day }}
                          </span>

                          <div class="data-box @if($day['date']->isPast() || $day['is_holiday'] || (isset($day['calendar_day']) && $day['calendar_day']->reservation_frames  === 0)) bg-gray @endif">
                            <!-- holiday and reservation acceptance -->
                            @if($day['is_holiday'])
                              <span class="day-label text-red">休</span>
                            @elseif(!$day['date']->isPast())
                              <a class="is_reservation_acceptance day-label">
                                {{ isset($day['calendar_day']) && $day['calendar_day']->is_reservation_acceptance == '0' ? '✕' : '◯' }}
                              </a>
                            @else
                              <span class="day-label">&nbsp;</span>
                            @endif
                            <input type="hidden" name="is_reservation_acceptances[]" value="{{ isset($day['calendar_day']) ? $day['calendar_day']->is_reservation_acceptance : 1 }}">

                            <!-- reservation frame -->
                            @if($day['date']->isPast())
                              {{  isset($day['calendar_day']) ? $day['calendar_day']->calendar_frame : 0}}
                              <input type="hidden" name="reservation_frames[]" value="{{  isset($day['calendar_day']) ? $day['calendar_day']->reservation_frames : 0}}" />
                            @else
                              @php
                                $reservation_frames = 0;
                                if((isset($day['calendar_day']) && $day['calendar_day']->is_reservation_acceptance == '0') || $day['is_holiday'] == 1) {
                                  $reservation_frames = '';
                                } else if (isset($day['calendar_day'])) {
                                  $reservation_frames = $day['calendar_day']->reservation_frames;
                                }
                              @endphp
                              <select name="reservation_frames[]" @if((isset($day['calendar_day']) && $day['calendar_day']->is_reservation_acceptance == '0') || $day['is_holiday']) disabled  @endif class='calendar-frame mt-1' data-day="{{ $day['date']->day }}"
                                      @if($day['is_holiday']) data-holiday="true" @endif
                                      data-origin="{{ $reservation_frames }}">
                                <option></option>
                                @foreach(range(0, 99) as $i)
                                  <option @if($reservation_frames === $i) selected @endif>
                                    {{ $i }}
                                  </option>
                                @endforeach
                              </select>
                            @endif

                            <!-- reservation count -->
                            <span class="reservation-count mb-4">予約 : {{ isset($day['reservation_count']) ? $day['reservation_count'] : 0 }}</span>
                          </div>
                        </td>
                      @else
                        <td>
                          <span class="day-label">&nbsp;</span>
                          <div class="data-box"></div>
                        </td>
                      @endif
                    @endforeach
                  </tr>
                @endforeach
                </tbody>
              </table>
          </div>
          @if($index % 2 == 0)
            <div class="clearfix"></div>
          @endif
          @php
            $index++
          @endphp
        @endforeach
        </div>
      </div>
      <div class="box-footer">
        <a href="{{ route('calendar.index') }}" class="btn btn-default">戻る</a>
        <button class="btn btn-primary" id="clear-data">期間限定・予約枠の数全てクリア</button>
        <button class="btn btn-primary" id="reset-data">設定のクリア</button>
        <button class="btn btn-primary" id="clear-data">登録する</button>
      </div>
    </div>
  </form>

  <style>
    .top-table td, .top-table th, .calendar-table th {
      text-align: center;
    }
    .calendar-table tbody tr td {
      padding: 0px;
    }
    .calendar-table td a {
      cursor: pointer;
    }
    .table-borderless tbody tr td{
      border: none;
    }
    .month-week label {
      display: block;
    }
    .month-week h4 {
      margin-top: 0px;
    }
    td .day-label {
      display: block;
      text-align: center;
    }
    .calendar-table td {
      text-align: center;
    }
    .reservation-count {
      margin-top: 4px;
      display: block;
      text-align: center;
    }
    .data-box {
      padding-top: 5px;
      border-top: 1px solid #f4f4f4;
      height: 90px;
    }
    .holiday {
      /*background-color: rgba(254, 109, 104, .6) !important;*/
      background-color: #FCE4E4;
    }
    .saturday {
      background-color: #CBE0F8;
    }
    select:disabled {
      cursor: not-allowed;
      background-color: #eee;
      opacity: 1;
    }
  </style>
@stop

@section('script')
  <script>
      (function ($) {
          /* ---------------------------------------------------
          // reservation accept/unaccept
          -----------------------------------------------------*/
          (function () {
              $('.is_reservation_acceptance').click(function() {
                  if($(this).html() == '✕') {
                      $(this).html('◯');
                      $(this).next('input:hidden').val('1');
                      $(this).siblings('select')
                             .prop('disabled', false)
                             .val('0')
                             .change();
                  } else {
                      $(this).html('✕');
                      $(this).next('input:hidden').val('0');
                      $(this).siblings('select')
                             .prop('disabled', true)
                             .val('')
                             .change();
                  }
              });
          })();

          /* ---------------------------------------------------
          // all month
          -----------------------------------------------------*/
          (function () {
              $('#all-month').change(function() {
                  if ($(this).prop('checked')) {
                    $('.month').prop('checked', true);
                  } else {
                      $('.month').prop('checked', false);
                  }
              });
          })();

          /* ---------------------------------------------------
          // all week
          -----------------------------------------------------*/
          (function () {
              $('#all-week').change(function() {
                  if ($(this).prop('checked')) {
                      $('.week').prop('checked', true);
                  } else {
                      $('.week').prop('checked', false);
                  }
              });
          })();

          /* ---------------------------------------------------
          // bulk update
          -----------------------------------------------------*/
          (function () {
              $('#bulk-update').click(function(event) {
                  event.preventDefault();
                  event.stopPropagation();

                  const holidayFrame=  $('#holiday-frame').val();
                  const frames = [
                      $('#sunday-frame').val(),
                      $('#monday-frame').val(),
                      $('#tuesday-frame').val(),
                      $('#wednesday-frame').val(),
                      $('#thursday-frame').val(),
                      $('#friday-frame').val(),
                      $('#saturday-frame').val()
                  ];

                  $('.calendar-table').each(function(i, table) {
                      if ($('#month-' + i).prop('checked')) {
                          $(table).find('.calendar-frame').each(function(j, ele){
                              ele = $(ele);
                              const day = parseInt(ele.data('day'));
                              const isHoliday = ele.data('holiday');

                              if (isHoliday) {
                                  ele.val(holidayFrame);
                              } else {
                                  let weekKey = '#week-';
                                  if(day >= 1 && day <= 7) {
                                      weekKey += 1;
                                  } else if(day >= 8 && day <= 14) {
                                      weekKey += 2;
                                  } if(day >= 15 && day <= 21) {
                                      weekKey += 3;
                                  } if(day >= 22 && day <= 28) {
                                      weekKey += 4;
                                  } else if(day > 28) {
                                      weekKey += 5;
                                  }

                                  if ($(weekKey).prop('checked')) {
                                      ele.val(frames[ele.parents('td').index()]);
                                  }
                              }
                          });
                      }
                  });
              });
          })();

          /* ---------------------------------------------------
          // clear data
          -----------------------------------------------------*/
          (function () {
              $('#clear-data').click(function(event) {
                  event.preventDefault();
                  event.stopPropagation();
                  $('.calendar-frame ').val('0');
              });
          })();

          /* ---------------------------------------------------
          // reset data
          -----------------------------------------------------*/
          (function () {
              $('#reset-data').click(function(event) {
                  event.preventDefault();
                  event.stopPropagation();
                  $('.calendar-frame').each(function(i, ele){
                      $(ele).val($(ele).data('origin'));
                  });
              });
          })();

          /* ---------------------------------------------------
          // reservation frame change
          -----------------------------------------------------*/
          (function () {
              const change = function(ele) {
                  const parentDiv = ele.parents('.data-box')
                  if (ele.val() == 0) {
                      parentDiv.addClass('bg-gray');
                  } else {
                      parentDiv.removeClass('bg-gray');
                  }
              };
              $('.calendar-frame').each(function(index, ele) {
                 ele = $(ele);
                 ele.change(function() {
                    change(ele);
                  });
                  change(ele);
              });
          })();
      })(jQuery);

      /* ---------------------------------------------------
      // scroll to top feature
      -----------------------------------------------------*/
      addScrollToTop();
  </script>
@stop