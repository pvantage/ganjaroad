<div class="block block_near_me right-col">
	<?php /*
	<h3 class="block-title">@lang('front/general.whats_current')</h3>
	<div class="content">
		<dl>
			{{--*/ $categories = TemplateHelper::getBlogCategories() /*--}}
			@if($categories)
				@foreach ($categories as $category)
					<dt><a href="{{ TemplateHelper::blogCategoryLink($category->id, $category->slug) }}">{{ $category->title }}</a></dt>
				@endforeach
			@endif
		</dl>
	</div>
	*/ ?>
	<div class="first-col">
		{!! TemplateHelper::renderRightBlock() !!}
	</div>
	{!! TemplateHelper::generateAdd('right') !!}
</div> 