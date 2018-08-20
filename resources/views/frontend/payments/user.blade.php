@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.my_transactions')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.responsive.css') }}" />

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/payments.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('my-account') }}">@lang('front/general.user_account')</a></li>
		<li class="active">@lang('front/general.my_transactions')</li>
	</ol>               
@stop

@section('leftcol')
	@section('leftcol_content')
		@include('layouts/user_leftcol')
	@stop
	@parent
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.my_transactions')</h1>
		</div>
		<hr>
		<div class="content my-transactions">
			@if(!empty($transactions))
				<p <?php /*class="hidden-lg"*/ ?>>@lang('front/general.table_green_plus_notice')</p>
				<table class="table table-bordered" id="table">
					<thead>
						<tr class="filters">
							<th>@lang('payments/table.transaction_id')</th>
							<th>@lang('payments/table.items')</th>
							<th>@lang('payments/table.amount')</th>
							<th>@lang('payments/table.status')</th>
							<th>@lang('payments/table.created_at')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($transactions as $transaction)
							<tr>
								<td>{{ $transaction->transaction_id }}</td>
								<td>
									@php
										$i = 0;
									@endphp
									
									@if($transaction->items->count())
										@foreach($transaction->items as $item)
											{{ $item->getItemTitle() }}
											@if(++$i < $transaction->items->count())
											,
											@endif
										@endforeach
									@endif
								</td>
								<td>{{ TemplateHelper::convertPrice($transaction->amount) }}</td>
								<td>
									<span class="payments-status{{ $transaction->paid }}">
										@if($transaction->paid)
											@lang('payments/title.payment_success')
										@else
											@lang('payments/title.payment_failed')
										@endif
									</span>
								</td>
								<td>{{ $transaction->created_at->diffForHumans() }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<p>@lang('front/general.transactions_empty')</p>
			@endif
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="nearme_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('body').on('hidden.bs.modal', '.modal', function () {
		jQuery(this).removeData('bs.modal');
	});
	
	jQuery('#table').DataTable({
		responsive: true,
		"columnDefs": [{
		  "targets"  : 'no-sort',
		  "orderable": false,
		  "order": []
		}]
	});
});
</script>
@stop
