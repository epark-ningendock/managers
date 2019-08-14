
@if ( $calendars && count($calendars) > 0  )
	<div class="paginate-box">
			<a href="#" class="prev-next-link prev-link fl" style="float: left;">&lt;&lt;</a>
			<a href="#" class="prev-next-link next-link fr" style="float: right;">&gt;&gt;</a>
	</div>
	<table class="date-row table table-bordered hor-date-table">

		<thead>
			<tr class="year-label">
				<th colspan="7" class="text-center">{{  DateTime::createFromFormat('Y-m-d', $calendars->first()['date'])->format('Y') }}</th>
				<input type="hidden" name="reservation_date" id="reservation_date">
				{{--<input type="hidden" name="page_number" id="page_number" value="{{ $calendars->currentPage() }}">--}}
			</tr>
		</thead>

		<tbody>

		@foreach($calendars->chunk(7) as $week)

				<tr class="hide-tr">

					@foreach($week as $day)

					<td data-date="{{ $day['date'] }}" class="daybox {{  ( $day['is_holiday'] == 1  ) ? 'is-holiday' : 'it-can-reserve' }}">
						<div class="txt">
							{{ date('m', strtotime($day['date'])) }}月
							{{ date('d', strtotime($day['date']))  }}日
							({{  strtoupper(substr(date('l', strtotime($day['date'])), 0, 2) ) }})
						</div>
						<div class="des-box">-</div>						
					</td>
						@endforeach
				</tr>
		@endforeach
		</tbody>


</table>

@endif