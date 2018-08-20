@if($print)
<style>

</style>
@endif

<table class="table table-bordered" id="sales_table">
	<thead>
		<tr class="filters">
			@if(!$print)
				<th></th>
			@endif
			<th>@lang('sales/table.rep')</th>
			<th>@lang('sales/table.rep_email')</th>
			<th>@lang('sales/table.rep_location')</th>
			<th>@lang('sales/table.rep_users_amount')</th>
			@if(!$print)
				<th>@lang('sales/table.actions')</th>
			@endif
		</tr>
	</thead>
	<tbody>
		@if(!empty($users))
			@foreach($users as $user)
				<tr>
					@if(!$print)
						<td><input type="checkbox" name="ids[]" value="{{ $user->admin_id }}" /></td>
					@endif
					<td>{!! Form::Author($user->admin_id) !!}</td>
					<td>{!! $user->admin->email !!}</td>
					<td>{!! $user->admin->getFormatedAddress() !!}</td>
					<td>{{ TemplateHelper::convertPrice($user->admin->getSalesAmount()) }}</td>
					@if(!$print)
						<td>
							<a href="{{ route('sales/reps/info', $user->admin->id) }}">
								<i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="@lang('sales/table.sales_info')"></i>
							</a>
						</td>
					@endif
				</tr>
			@endforeach
		@endif
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
				@lang('sales/table.reps_grand_total')
			</td>
			<td colspan="2">
				
			</td>
		</tr>
	</tfoot>
</table>