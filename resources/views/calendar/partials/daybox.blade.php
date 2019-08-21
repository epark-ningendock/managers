
@if ( $calendars && count($calendars) > 0  )
	<div class="paginate-box">
			<a href="#" class="prev-next-link prev-link fl" style="float: left;">&lt;&lt;</a>
			<a href="#" class="prev-next-link next-link fr" style="float: right;">&gt;&gt;</a>
	</div>
	<table class="date-row table table-bordered hor-date-table">

		<thead>
			<tr class="year-label">
				<th colspan="7" class="text-center">{{  ($calendars->first()['date'])->format('Y') }}</th>
				<input type="hidden" name="reservation_date" id="reservation_date">
			</tr>
		</thead>

		<tbody>

		@foreach($calendars->chunk(7) as $week)

				<tr class="hide-tr">

					@foreach($week as $day)

					<td data-date="{{ $day['date']->format('Y-m-d') }}" class="daybox {{  ( $day['is_holiday'] || !$day['is_reservation_acceptance'] ) ? 'not-reservable' : 'it-can-reserve' }}
							@if($day['is_holiday'] || !$day['is_reservation_acceptance'] || $day['date']->isWeekend()) gray-background @endif">
						<div class="txt">
							{{ $day['date']->format('m') }}月
							{{ $day['date']->format('d')  }}日
							({{  strtoupper(substr($day['date']->format('l'), 0, 2)) }})
						</div>
						<div class="des-box">
							@if($day['is_holiday'] || !$day['is_reservation_acceptance'])
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

@endif