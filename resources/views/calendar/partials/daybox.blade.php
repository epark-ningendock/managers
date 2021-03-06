
@if ( $calendars && count($calendars) > 0  )
	<div class="paginate-box">
			<a href="#" class="prev-next-link prev-link fl btn btn-primary" style="float: left;">&lt;&lt;</a>
			<a href="#" class="prev-next-link next-link fr btn btn-primary" style="float: right;">&gt;&gt;</a>
	</div>
	<div id="reservation_calendar">
	<table class="date-row table table-bordered hor-date-table">

		<thead>
			<tr class="year-label">
				<th colspan="7">
					<a href="#" class="calendar-picker">
						<div class="input-group date datepicker" data-date-format="yyyy-mm-dd" data-provide="datepicker">
							<input type="hidden" id="startdate"/>
							<div class="input-group-addon">
								<img src="/img/calendar.png" width="25"></img>
							</div>
						</div>
					</a>
					<span id="year-label">{{ ($calendars->first()['date'])->format('Y') }}</span>
				</th>
				<input type="hidden" name="reservation_date" id="reservation_date">
			</tr>
		</thead>

		<tbody>

		@foreach($calendars->chunk(7) as $week)

				<tr class="hide-tr">

					@foreach($week as $day)

					<td data-date="{{ $day['date']->format('Y-m-d') }}" class="daybox {{  ( $day['is_holiday'] || $day['is_reservation_acceptance'] || $day['frame'] <= 0 ) ? 'not-reservable' : 'it-can-reserve' }}
							@if($day['date']->isSaturday()) blue-background @endif>
							@if($day['date']->isSunday() || isset($day['holiday'])) red-background @endif>
							@if($day['is_holiday'] || $day['is_reservation_acceptance'] || $day['frame'] <= 0) gray-background @endif">
						<div class="txt">
							{{ $day['date']->format('m') }}月
							{{ $day['date']->format('d')  }}日
							({{ strtoupper(substr($day['date']->format('l'), 0, 2)) }})
						</div>
						<div class="des-box">
							@if($day['is_holiday'] || $day['is_reservation_acceptance'] || $day['frame'] <= 0 )
								−
							@else
								◯
							@endif
						</div>
					</td>
						@endforeach
				</tr>
		@endforeach
		</tbody>


</table>
	</div>
@endif