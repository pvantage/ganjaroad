{{--*/ $categories = TemplateHelper::getNearMeCategories() /*--}}

@if ($categories)
	<div class="block block_near_me">
		<h3 class="block-title">@lang('front/general.front_near_me')</h3>
		<h4 class="block-subtitle">
			<img src="{{ asset('assets/images/location.png') }}" srcset="{{ asset('assets/images/location.png 1x') }}, {{ asset('assets/images/location@2x.png 2x') }}" alt="Near Me" width="18" height="18">
			{!! TemplateHelper::getNearMeCurrentHeader() !!}
		</h4>
		
		<div class="content">
			<dl>
				@foreach ($categories as $category)
					{{--*/ $nearmes = TemplateHelper::getNearmeFromCategory($category->id) /*--}}
					<dt><a href="{!! TemplateHelper::nearMeCategoryLink($category->id, $category->slug) !!}">{{ $category->title }}</a></dt>
					<dd>
						<ul>
							@foreach ($nearmes as $nearme)
								<li>
									<a href="{!! TemplateHelper::nearMeLink($nearme->id, $nearme->slug) !!}">{{ $nearme->title }}:  {!! TemplateHelper::formatDistance($nearme->distance) !!}</a>
								</li>
							@endforeach
						</ul>
					</dd>
				@endforeach
			</dl>
		</div>
	</div>
@endif