@if($type && $items && count($items))
	<div class="items-wrapper">
		<h3>@lang('front/general.review_pending_'.$type)</h3>
		<div class="table-wrapper">
			<table class="table table-striped">
				<thead class="thead-inverse">
					<tr>
						<th class="col-md-2">@lang('front/general.qty')</th>
						<th class="col-md-5">@lang('front/general.item')</th>
						<th class="col-md-3">@lang('front/general.price')</th>
						<th class="col-md-2">@lang('front/general.review_add')</th>
					</tr>
				</thead>
				<tbody>
					@foreach($items as $item)
						{{--*/ $price = TemplateHelper::getItemPrice($type, $item) /*--}}
						@if($item)
							<tr>
								<th scope="row">{{ TemplateHelper::getAddToCartQty($type, $item) }}</th>
								<td>{{ $item->title }}</td>
								<td>{{ TemplateHelper::convertPrice($price) }}</td>
								<td>
									<a href="{{ route('review/item/add/type', [$item->id, $type]) }}">
										<i class="fa fa-check-square-o" aria-hidden="true"></i>
									</a>
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endif