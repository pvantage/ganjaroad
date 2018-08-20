@if($history = $item->getTransactionsHistory())
	<div class="row">
		<h2>@lang('admin.transaction_history')</h2>
		<div class="col-sm-8">		
			
				<table class="table table-bordered" id="transactions">
					<thead>
						<tr class="filters">
							<th>@lang('admin.transaction_id')</th>
							<th>@lang('admin.transaction_type')</th>
							<th>@lang('admin.amount')</th>
							<th>@lang('admin.created_at')</th>
						</tr>
					</thead>
					<tbody>
						@foreach($history as $transaction)
							<tr>
                                <td>{{ $transaction['transaction_id'] }}</td>
                                <td>{{ $transaction['type'] }}</td>
                                <td>{{ $transaction['amount'] }}</td>
                                <td>{{ $transaction['created_at'] }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
		</div>
	</div>
@endif