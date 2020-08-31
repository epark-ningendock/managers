@extends('layouts.list')

@push('css')
	<style>
		#MailPreview{ background: #f4fbff; padding: 2em; height: 40em; margin: 2em; box-shadow: 2px 2px 4px; border-radius: 2px }
		#MailBody{ width: 100%; height: 30em; overflow-y: scroll }
		.table-hover tbody tr:hover{ cursor: pointer }
		input[type="datetime-local"]::-webkit-clear-button{ -webkit-appearance: none; display: none }
	</style>
@endpush

@push('js')
	<script>
		$(function(){
			$('button[type="reset"]').on('click', function(){
				$('.datetimepicker').find('input').attr('value', null);
			});
			$('table tbody tr').on('click', function(){
				const id = $(this).data('id');
				$.ajax({
					url: "{{ route('sentmail.index') }}/" + id,
					type: 'GET'
				}).done(function(data){
					var body = data.body.replace(/\s?<[^>]*>\s?/gm, '');
					$('#MailPreview').find('.date').text(data.date).end()
					.find('#to').val(data.to).end()
					.find('.subject').text(data.subject).end()
					.find('#MailBody').val(body).end();
				});
			})
		});
	</script>
@endpush

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
	<h1>
		<i class="fa fa-mail-forward"> メール送信履歴</i>
	</h1>
@stop

@section('search')
	<form action="{{ route('sentmail.index') }}" method="get" role="form">
		<div class="row">
			<div class="col-md-4">
				<label>送信日時</label>
				<div class="form-inline">
					<div class="input-group datetimepicker">
						<input type="text" class="form-control" name="date_from" placeholder="yyyy-mm-dd H:i" value="{{ $request->date_to or '' }}" autocomplete="off">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>
					〜
					<div class="input-group datetimepicker">
						<input type="text" class="form-control" name="date_to" placeholder="yyyy-mm-dd H:i" value="{{ $request->date_to or '' }}" autocomplete="off">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-5">
				<label>受信メールアドレス</label>
				<div class="form-inline">
					<input type="text" class="form-control" name="email_account" value="{{ $request->email_account }}">@<input type="text" class="form-control" name="email_domain" value="{{ $request->email_domain }}">
				</div>
			</div>

			<div class="col-md-3 text-right">
				<label>　</label>
				<div class="form-inline">
					<button type="reset" class="btn btn-default">クリア</button>
					<button type="submit" class="btn btn-primary ml-4"><i class="glyphicon glyphicon-search"></i> 検索</button>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<label>フリーワード</label>
				<div class="form-inline">
					<input type="text" class="form-control" name="freeword" value="{{ $request->freeword }}" style="width: 100%">
				</div>
			</div>
		</div>
	</form>
@stop

@section('table')
	<div class="row">
		<div class="col-md-5">
			<div class="table-responsive">
				@include('layouts.partials.pagination-label', ['paginator' => $data])
				@foreach($data as $d)
					@if($loop->first)
						<table class="table mb-5 table-hover">
					@endif

						<tr data-id="{{ $d->id }}">
							<td>
								<div class="row">
									<div class="col-md-7 text-left">
										@if($d->attachments)<i class="glyphicon glyphicon-file"></i>@else<i class="glyphicon"></i>@endif
										{{ $d->to }}
									</div>
									<div class="col-md-5 text-right">{{ \Carbon\Carbon::parse($d->date)->format('Y-m-d H:i:s') }}</div>
								</div>
								<div class="text-left"><strong>{{ $d->subject }}</strong></div>
								<div class="text-left mt-3">{!! mb_substr(strip_tags($d->body), 0, 60) !!}...</div>
							</td>
							</tr>
							@if($loop->last)
						</table>
					@endif
				@endforeach
			</div>
		</div>
		<div class="col-md-7">
			<div id="MailPreview">
				<div>
					<strong>送信日時：</strong><span class="date"></span>
				</div>
				<div>
					<div class="form-inline">
						<strong>受信メールアドレス：</strong>
						<input type="text" id="to" class="form-control" style="width: 24em" readonly>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 mt-4">
						<strong>表題：</strong>
						<span class="subject"></span>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<textarea id="MailBody" readonly></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@include('commons.datetimepicker')
