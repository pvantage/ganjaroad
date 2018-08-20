@if($print)
	<style>
	</style>
@endif

<table class="table table-bordered" id="transactions">
	<thead>
		<tr class="filters">
			<th>@lang('sales/table.transaction_id')</th>
			<th>@lang('sales/table.tranasction_type')</th>
			<th>@lang('sales/table.item_name')</th>
			<th>@lang('sales/table.item_type')</th>
			<th>@lang('sales/table.amount')</th>
			<th>@lang('sales/table.created_at')</th>
		</tr>
	</thead>
	<tbody>
		@if($transactions)
			@foreach($transactions as $transaction)
				@foreach($transaction->items as $item)
					<tr>
						<td>{{ $transaction->transaction_id }}</td>
						<td>{{ $transaction->tranasction_type }}</td>
						<td>
							@if($item->getItem())
								@if($print)
									{{ $item->getItem()->title }}
								@else
									<a href="{{ TemplateHelper::getItemAdminLink($item->getItem()->getType(), $item->getItem()->id) }}" target="_blank">{{ $item->getItem()->title }}</a>
								@endif
							@else
								@lang('sales/table.does_not_exist')
							@endif
						</td>
						<td>
							@if($item->getItem())
								{{ $item->getItem()->getType() }}
							@else
								@lang('sales/table.does_not_exist')
							@endif
						</td>
						<td>{!! TemplateHelper::convertPrice($item->paid) !!}</td>
						<td>{{ $item->created_at }}</td>
					</tr>
				@endforeach
			@endforeach
		@endif
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
				@lang('sales/table.grand_total')
			</td>
			<td colspan="2">
				{!! TemplateHelper::convertPrice($user->getAmountSpent()) !!}
			</td>
		</tr>
	</tfoot>
</table>