<div class="block_list">
	<?php $categories = TemplateHelper::getClassifiedsCategoryByParent(); ?>
	@if ($categories->count())
		@foreach ($categories as $category)
			<div class="block block_classifieds">
				<h3 class="block-title">{{ $category->title }}</h3>
				<div class="content">
					<ul class="column">
						<?php $sub_categories = TemplateHelper::getClassifiedsCategoryByParent($category->id); ?>
						@if ($sub_categories->count())
							@foreach ($sub_categories as $sub)
								<li><a href="{!! TemplateHelper::classifiedsCategoryLink($sub->id, $sub->slug) !!}">{{ $sub->title }}</a></li>
							@endforeach
						@endif
					</ul>
				</div>
			</div>
		@endforeach
	@endif
</div>