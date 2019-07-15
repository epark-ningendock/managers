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
    <i class="fa fa-calendar"> 休日管理</i>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('calendar.updateHoliday') }}">
    {!! csrf_field() !!}
    {!! method_field('PATCH') !!}
    <div class="box-body">
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
                <select id="sunday">
                  <option></option>
                  <option value="1">休</option>
                </select>
              </td>
              <td>
                <select id="monday">
                  <option></option>
                  <option value="1">休</option>
                </select>
              </td>
              <td>
                <select id="tuesday">
                  <option></option>
                  <option value="1">休</option>
                </select>
              </td>
              <td>
                <select id="wednesday">
                  <option></option>
                  <option value="1">休</option>
                </select>
              </td>
              <td>
                <select id="thursday">
                  <option></option>
                  <option value="1">休</option>
                </select>
              </td>
              <td>
                <select id="friday">
                  <option></option>
                  <option value="1">休</option>
                </select>
              </td>
              <td>
                <select id="saturday">
                  <option></option>
                  <option value="1">休</option>
                </select>
              </td>
              <td>
                <select id="holiday">
                  <option></option>
                  <option value="1">休</option>
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
      @php
        $index = 1
      @endphp
      <div class="pull-right">
        <button class="btn btn-primary page-button" data-index="0"><span class="glyphicon glyphicon-chevron-left"></span></button>
        <span class="ml-2 mr-2">1/2 ページ</span>
        <button class="btn btn-primary page-button" data-index="1"><span class="glyphicon glyphicon-chevron-right"></span></button>
      </div>
      @foreach($months->chunk(6) as $c_months)
        <div class="row page">
          @foreach($c_months as $key => $month)
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
                        <td class="@if($day['date']->isSunday() || (isset($day['date']) && $day['is_holiday'])) holiday @elseif($day['date']->isSaturday()) saturday @endif">
                          <!-- date -->
                          <input type="hidden" name="days[]" value="{{ $day['date']->format('Ymd') }}" />
                          <span class="day-label @if($day['date']->isSunday() || ($day['is_holiday'])) text-red @elseif($day['date']->isSaturday()) text-blue @endif">
                            {{ $day['date']->day }}
                          </span>

                          <div class="data-box @if($day['is_holiday']) holiday @endif">
                            <!-- reservation frame -->

                            <select name="is_holidays[]" class='is-holiday mt-1' data-day="{{ $day['date']->day }}"
                                    @if($day['is_holiday']) data-holiday="true" @endif
                                    data-origin="{{ $day['is_holiday'] }}">
                              <option></option>
                              <option value="1" @if($day['is_holiday']) selected @endif>休</option>
                            </select>


                            <!-- holiday name -->
                            @if(isset($day['holiday']))
                              <br/>
                              <span class="text-red small mt-2">{{ $day['holiday']->getName() }}</span>
                            @endif
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
      @endforeach

      </div>
      <div class="box-footer">
        <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
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
      width: 14%;
    }
    .data-box {
      padding-top: 5px;
      border-top: 1px solid #f4f4f4;
      height: 55px;
    }
    .holiday {
      /*background-color: rgba(254, 109, 104, .6) !important;*/
      background-color: #FCE4E4;
    }
    .saturday {
      background-color: #CBE0F8;
    }
    .small {
      font-size: .55vw;
    }
  </style>
@stop

@section('script')
  <script>
      (function ($) {
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

                  const holiday=  $('#holiday').val();
                  const holidays = [
                      $('#sunday').val(),
                      $('#monday').val(),
                      $('#tuesday').val(),
                      $('#wednesday').val(),
                      $('#thursday').val(),
                      $('#friday').val(),
                      $('#saturday').val()
                  ];

                  $('.calendar-table').each(function(i, table) {
                      if ($('#month-' + i).prop('checked')) {
                          $(table).find('.is-holiday').each(function(j, ele){
                              ele = $(ele);
                              const day = parseInt(ele.data('day'));
                              const isHoliday = ele.data('holiday');

                              if (isHoliday) {
                                  ele.val(holiday);
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
                                      ele.val(holidays[ele.parents('td').index()]);
                                  }
                              }
                          });
                      }
                  });
              });
          })();

          /* ---------------------------------------------------
          // reset data
          -----------------------------------------------------*/
          (function () {
              $('#reset-data').click(function(event) {
                  event.preventDefault();
                  event.stopPropagation();
                  $('.is-holiday').each(function(i, ele){
                      $(ele).val($(ele).data('origin'));
                  });
              });
          })();

          /* ---------------------------------------------------
          // calendar paging
          -----------------------------------------------------*/
          (function () {
              $('.page-button').click(function(event) {
                  event.preventDefault();
                  event.stopPropagation();
                  $(this).prop('disabled', true);
                  $(this).siblings('button').prop('disabled', false);
                  const index = parseInt($(this).data('index'));
                  $('.page').hide();
                  $($('.page').get(index)).show();

                  $(this).siblings('span').html( (index + 1) + '/2ページ');
              });

              $('.page').last().hide();
              $('.page-button').first().prop('disabled', true);
          })();
      })(jQuery);

      /* ---------------------------------------------------
      // scroll to top feature
      -----------------------------------------------------*/
      addScrollToTop();
  </script>
@stop