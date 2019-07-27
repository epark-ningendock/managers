@if ( $calendars && count($calendars) > 0  )
	<div class="paginate-box">
		
		@if ( $calendars->previousPageUrl() ) 
			<a href="{{  $calendars->previousPageUrl() }}" class="prev-next-link prev-link fl" style="float: left;">&lt;&lt;</a>
		@endif
		
		@if ( $calendars->nextPageUrl() ) 
			<a href="{{  $calendars->nextPageUrl() }}" class="prev-next-link next-link fr" style="float: right;">&gt;&gt;</a>
		@endif

	</div>
	<table class="date-row table table-bordered">

		<thead>
			<tr class="year-label">
				<th colspan="7" class="text-center">{{  $calendars[0]->date->format('Y') }}</th>
				<input type="hidden" name="reservation_date" id="reservation_date">
				<input type="hidden" name="page_number" id="page_number" value="{{ $calendars->currentPage() }}">
			</tr>
		</thead>

		<tbody>
			<tr class="">
				@foreach($calendars as $calendar)
					<td data-date="{{  $calendar->date->format('Y-m-d') }}" class="daybox {{  ( isset($holidays) && in_array($calendar->date, $holidays) ) ? 'is-holiday' : 'it-can-reserve' }}">
						<div class="txt">
							{{  $calendar->date->format('m') }}月
							{{  $calendar->date->format('d') }}日
							({{  strtoupper(substr($calendar->date->format('l'), 0, 2)) }})
						</div>
						<div class="des-box">-</div>						
					</td>
				@endforeach				
			</tr>
		</tbody>		

	@foreach($calendars as $calendar)

	@endforeach

</table>

@endif