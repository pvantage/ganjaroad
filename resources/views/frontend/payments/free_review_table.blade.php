@if($type && $items && count($items))
	<div class="items-wrapper">
		<h3>@lang('front/general.review_pending_'.$type)</h3>
		<div class="table-wrapper">
			<table class="table table-striped">
				<thead class="thead-inverse">
					<tr>
						<th class="col-md-2">@lang('front/general.qty')</th>
						<th class="col-md-5">@lang('front/general.item')</th>
						<!--<th class="col-md-3">@lang('front/general.price')</th>-->
                        <th>Renewal</th>
                        <th>Delete</th>
					</tr>
				</thead>
				<tbody>
					@foreach($items as $item)
						{{--*/ $price = TemplateHelper::getItemPrice($type, $item) /*--}}
						@if($item)
							<tr>
								<th scope="row">{{ TemplateHelper::getAddToCartQty($type, $item) }}</th>
								<td>{{ $item->title }}</td>
								<!--<td>{{ TemplateHelper::convertPrice($price) }}</td>-->
                                <td>
                                    @if(count($adrenewoptions[$type]) > 0)
                                        <?php
                                            $adrenewoptionSelect = [];
                                            foreach($adrenewoptions[$type] as $adrenewoption) {
                                                $adrenewoptionSelect[$adrenewoption->id] = TemplateHelper::getDiscountOptionName($adrenewoption->renewal_period_name, $adrenewoption->renewal_discount);
                                            }
                                        ?>
                                        {!! Form::select($type.'['.$item->id.'][renewal]' , $adrenewoptionSelect) !!}
                                    @endif
                                </td>
                                <td>{!! Form::checkbox($type.'['.$item->id.'][delete]') !!}</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endif
