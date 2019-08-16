@extends('layouts.form')

@section('content_header')
  <h1>
      <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
      -
      <span>休日管理</span>
  </h1>
@stop

@section('form')
  <form method="POST" action="{{ route('calendar.updateHoliday') }}">
    <div id="calendar_bulk_box">
    {!! csrf_field() !!}
    {!! method_field('PATCH') !!}
    <div class="box-body">
      <p class="calendar-period">期間 <span class="ml-2 mr-2"> : </span> {{ $start->format('Y/m/d').' ~ '.$end->format('Y/m/d') }}<button type="button" id="open-bulk-box" class="btn btn-light"><i class="fa fa-angle-down"></i>一括登録設定</button></p>
      {!! csrf_field() !!}
      <div class="bulk_update">
          <h2>休診日設定 <span>休診に設定する曜日を選択してください。</span></h2>
          <div class="bulk-weekday">
              <ul>
                  <li><label for="sunday-frame">日</label>
                      <select id="sunday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
                  <li><label for="monday-frame">月</label>
                      <select id="monday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
                  <li><label for="tuesday-frame">火</label>
                      <select id="tuesday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
                  <li><label for="wednesday-frame">水</label>
                      <select id="wednesday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
                  <li><label for="thursday-frame">木</label>
                      <select id="thursday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
                  <li><label for="friday-frame">金</label>
                      <select id="friday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
                  <li><label for="saturday-frame">土</label>
                      <select id="saturday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
                  <li><label for="holiday-frame">祝</label>
                      <select id="holiday" class="form-control select-box w4em">
                          <option value="0"></option>
                          <option value="1">休</option>
                      </select>
                  </li>
              </ul>
          </div>
          <div class="to-month">
              <h2>対象月<span>設定する月をチェックしてください</span></h2>
              <p><input type="checkbox" id="all-month" class="month"/><label for="all-month"> すべて選択</label></p>
              @foreach($months->keys() as $index => $month)
                  <p><input type="checkbox" id="month-{{ $index }}" class="month" data-index="{{ $index }}"/><label for="month-{{ $index }}">{{ $month }}</label></p>
              @endforeach
          </div>
          <div class="to-week clearfix">
              <h2>対象週<span>設定する週をチェックしてください</span></h2>
              <p><input type="checkbox" class="week" id="all-week" /><label for="all-week"> すべて選択</label></p>
              <p><input type="checkbox" class="week" id="week-1" /> <label for="week-1">第1 (1日~7日)</label></p>
              <p><input type="checkbox" class="week" id="week-2" /> <label for="week-2">第2 (8日~14日)</label></p>
              <p><input type="checkbox" class="week" id="week-3" /> <label for="week-3">第3 (15日~21日)</label></p>
              <p><input type="checkbox" class="week" id="week-4" /> <label for="week-4">第4 (22日~28日)</label></p>
              <p><input type="checkbox" class="week" id="week-5" /> <label for="week-5">第5 (29日~)</label></p>
          </div>
          <button class="btn btn-primary pull-right" id="bulk-update">一括反映</button>
      </div>
      @php
        $index = 1
      @endphp
      <div class="pagenate">
          <div class="page-link">
            <button class="btn btn-primary page-button" data-index="0"><span class="glyphicon glyphicon-chevron-left"></span></button>
            <span class="ml-2 mr-2">1/2 ページ</span>
            <button class="btn btn-primary page-button" data-index="1"><span class="glyphicon glyphicon-chevron-right"></span></button>
          </div>
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
                        <td class="@if($day['date']->isSaturday()) saturday @elseif($day['date']->isSunday() || isset($day['holiday'])) holiday @endif">
                          <!-- date -->
                          <input type="hidden" name="days[]" value="{{ $day['date']->format('Ymd') }}" />
                          <input type="hidden" name="lock_versions[]" value="{{ $day['lock_version'] or '' }}" />
                          <span class="day-label @if($day['date']->isSunday()) text-red @elseif($day['date']->isSaturday()) text-blue @endif">
                            {{ $day['date']->day }}
                          </span>

                          <div class="data-box @if($day['date']->isSunday()) holiday @endif">
                            <!-- reservation frame -->

                            <select name="is_holidays[]" class='is-holiday mt-1' data-day="{{ $day['date']->day }}"
                                    @if($day['is_holiday']) data-holiday="true" @endif
                                    @if(isset($day['holiday'])) data-holiday-name="{{ $day['holiday']->getName() }}" @endif
                                    data-origin="{{ $day['is_holiday'] }}">
                              <option value="0"></option>
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
        <a href="{{ route('calendar.index') }}" class="btn btn-default">戻る</a>
        <button class="btn btn-primary" id="reset-data">設定のクリア</button>
        <button class="btn btn-primary" id="clear-data">登録する</button>
      </div>
    </div>
  </form>
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
                              const holidayName = ele.data('holiday-name');

                              if (holidayName) {
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
                      $(ele).val('0');
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