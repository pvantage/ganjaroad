<style>
.green {
	color: green;
}
</style>

<table class="table table-bordered" id="sales_table">
	<thead>
		<tr class="filters">
			@if(!$print)
				<th></th>
			@endif
			<th>@lang('sales/table.user_name')</th>
			<th>@lang('sales/table.user_email')</th>
			<th>@lang('sales/table.user_location')</th>
			@if(!$print)
				<th>@lang('sales/table.type_ads')</th>
				<th>@lang('sales/table.type_classified')</th>
				<th>@lang('sales/table.type_nearme')</th>
			@endif
			<th>@lang('sales/table.users_spent_amount')</th>
			<th>@lang('sales/table.last_payment_at')</th>
			@if(!$print)
				<th class="never"></th>
				<th class="never"></th>
				<th class="never"></th>
				<th>@lang('sales/table.actions')</th>
			@endif
		</tr>
	</thead>
	<tbody>
		@if($print && !empty($users))
			@foreach($users as $user)
				<tr>
					<td>{!! Form::Author($user->id) !!}</td>
					<td>{!! $user->email !!}</td>
					<td>{!! $user->getFormatedAddress() !!}</td>
					@if(!$print)
						<td>
							@if($user->ads->count())
								<span class="green">Y</span>
							@else
								<span class="black">N</span>
							@endif
						</td>
						<td>
							@if($user->classifieds->count())
								<span class="green">Y</span>
							@else
								<span class="black">N</span>
							@endif
						</td>
						<td>
							@if($user->nearme->count())
								<span class="green">Y</span>
							@else
								<span class="black">N</span>
							@endif
						</td>
					@endif
					<td>{{ TemplateHelper::convertPrice($user->getAmountSpent()) }}</td>
					<td>
						@if($user->payments()->count())
							{!! $user->payments()->latest()->first()->created_at !!}
						@endif
					</td>
				</tr>
			@endforeach
		@endif
	</tbody>
</table>