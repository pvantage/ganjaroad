<div class="welcome">
	<h1>{{$nearme->title}}</h1>
</div>
<hr>
<div class="content">
	<div class="panel-body">
		<div class="row">
            <div class="col-sm-6 col-md-5">
                @lang('front/general.click_on_image_for_more_info')
            </div>
            <div class="col-sm-6 col-md-4">
				<div class="form-group">
					{!! Form::select('items_categories', $items_categories, null, array('class' => 'form-control weed-select', 'placeholder' => trans('front/general.nearme_item_select_items_category'))) !!}
				</div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-show-prices">@lang('front/general.show_all_prices')</button>
            </div>
        </div>
		
		<div class="gallery">
			{{--*/ $i = 0 /*--}}
			@forelse($menu as $item)
				<div class="name-wrapper {{ $item->category->title }} all">
					<div class="gallery-item-wrapper">
						<div class="img-box">
							<img src="{!! $item->image() !!}" onclick="lightbox({{ $i}})" />
						</div>
						<div class="detail-box">
							<div class="box-title">
								<h2>{{ $item->name }}</h2>
								<p>{{ $item->category->title }}</p>
							</div>
							<div class="detail-parameter">
								@if($item->ea)
									<p>@lang('front/general.nearme_item_ea') <span>{{ $item->ea }}</span></p>
								@else
									<p>@lang('front/general.nearme_item_1g') <span>{{ $item->type_1g }}</span></p>
									<p>@lang('front/general.nearme_item_2g') <span>{{ $item->type_2g }}</span></p>
									<p>@lang('front/general.nearme_item_18') <span>{{ $item->type_18 }}</span></p>
									<p>@lang('front/general.nearme_item_14') <span>{{ $item->type_14 }}</span></p>
									<p>@lang('front/general.nearme_item_12') <span>{{ $item->type_12 }}</span></p>
									<p>@lang('front/general.nearme_item_oz') <span>{{ $item->type_oz }}</span></p>
								@endif
							</div>
						</div>
						<div class="two-detail-box">
						
							<div class="half">
								@if($item->thc)
									<p>@lang('front/general.nearme_item_thc') <span>{{ $item->thc }}</span></p>
								@else
									<p class="empty">@lang('front/general.nearme_item_thc') <span><strong>---</strong></span></p>
								@endif
							</div>
							<div class="half">
								@if($item->thc)
									<p>@lang('front/general.nearme_item_cbd') <span>{{ $item->cbd }}</span></p>
								@else
									<p class="empty">@lang('front/general.nearme_item_cbd') <span><strong>---</strong></span></p>
								@endif
							</div>
							<div class="half">
								@if($item->thc)
									<p>@lang('front/general.nearme_item_cbn') <span>{{ $item->cbn }}</span></p>
								@else
									<p class="empty">@lang('front/general.nearme_item_cbn') <span><strong>---</strong></span></p>
								@endif
							</div>
							<div class="half">
								@if($item->thc)
									<p>@lang('front/general.nearme_item_terpenes') <span>{{ $item->terpenes }}</span></p>
								@else
									<p class="empty">@lang('front/general.nearme_item_terpenes') <span><strong>---</strong></span></p>
								@endif
							</div>
							
						</div>
					</div>
				</div>
				<!-- {{ ++$i }} -->
			@empty

			@endforelse
            <div class="light-container" style="display:none;">
                <div id="ninja-slider">
                    <div class="slider-inner">
                        <ul>
							{{--*/ $i = 0 /*--}}
							@forelse($menu as $item)
								<li>
									<a class="ns-img" href="{!! $item->image() !!}"></a>
									<div class="caption">
										<div class="gallery-item-wrapper">
											<div class="detail-parameter">
												<ul>
													@if($item->ea)
														<li>@lang('front/general.nearme_item_ea') <span>{{ $item->ea }}</span></li>
													@else
														@if($item->thc)
															<li>@lang('front/general.nearme_item_thc') <span>{{ $item->thc }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_thc') <span><strong>---</strong></span></li>
														@endif
														
														@if($item->cbd)
															<li>@lang('front/general.nearme_item_cbd') <span>{{ $item->cbd }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_cbd') <span><strong>---</strong></span></li>
														@endif
														
														@if($item->cbn)
															<li>@lang('front/general.nearme_item_cbn') <span>{{ $item->cbn }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_cbn') <span><strong>---</strong></span></li>
														@endif
														
														@if($item->terpenes)
															<li>@lang('front/general.nearme_item_terpenes') <span>{{ $item->terpenes }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_terpenes') <span><strong>---</strong></span></li>
														@endif
														
														@if($item->type_1g)
															<li>@lang('front/general.nearme_item_1g') <span>{{ $item->type_1g }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_1g') <span><strong>---</strong></span></li>
														@endif
														@if($item->type_2g)
															<li>@lang('front/general.nearme_item_2g') <span>{{ $item->type_2g }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_2g') <span><strong>---</strong></span></li>
														@endif
														@if($item->type_18)
															<li>@lang('front/general.nearme_item_18') <span>{{ $item->type_18 }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_18') <span><strong>---</strong></span></li>
														@endif
														@if($item->type_14)
															<li>@lang('front/general.nearme_item_14') <span>{{ $item->type_14 }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_14') <span><strong>---</strong></span></li>
														@endif
														@if($item->type_12)
															<li>@lang('front/general.nearme_item_12') <span>{{ $item->type_12 }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_12') <span><strong>---</strong></span></li>
														@endif
														@if($item->type_oz)
															<li>@lang('front/general.nearme_item_oz') <span>{{ $item->type_oz }}</span></li>
														@else
															<li class="empty">@lang('front/general.nearme_item_oz') <span><strong>---</strong></span></li>
														@endif
													@endif
												</ul>
											</div>
											<div class="detail-box">
												<div class="box-title">
													<h2>{{ $item->name }}</h2>
													<h5>{{ $item->category->title }}</h5>
													@if($item->lab)
													<p class="lab">
														<span>@lang('front/general.nearme_item_lab'):</span>
														<a href="{{ $item->lab }}" target="_blank">{{ $item->lab }}</a>
													</p>
													@endif
													<p>{{ $item->description }}</p>
												</div>
											</div>
										</div>
									</div>
								</li>
							@empty
							@endforelse
                        </ul>
                        <div id="fsBtn" class="fs-icon" title="@lang('front/general.nearme_item_expand_close')"></div>
                    </div>
                </div>
            </div>
        </div>	
	</div> 
</div>